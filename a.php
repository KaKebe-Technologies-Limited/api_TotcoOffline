<?php

include '../dBCOnf1G.php';
include '../sanit1Z3r.php';
include '../p3pp3rConfiG.php'; //$pepper added..

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$response['success'] = 0;
$response['query2'] = $userName ;
    $response['query'] = $_REQUEST['raw_data'];
    $response['title'] = $PHPobj->title;
    $response['message'] = "Captured";
    $response['request'] = $_REQUEST;

if (isset($_REQUEST['raw_data'])  && $_REQUEST['raw_data'] !== "" ) {

    // Access and decode the json data send by post
    $jsonData = $_REQUEST['raw_data'];
    $PHPobj = json_decode($jsonData);

    $response['query2'] = $userName ;
    $response['query'] = $_REQUEST['raw_data'];
    $response['title'] = $PHPobj->title;
    $response['message'] = "Captured";
    $response['request'] = $_REQUEST;

    if ((isset($PHPobj->title) && $PHPobj->title != "") || (isset($PHPobj->paragraph) && $PHPobj->paragraph != "") || (isset($PHPobj->category) && $PHPobj->category != "")) {

        if ($userName = $PHPobj->username) {
            
            //handle cleaning here
            clean_input($userName);
            $response['user'] = $userName;
            
            // check fields have data and handle the situation
            $postTitle = isset($PHPobj->title) && $PHPobj->title != "" ? $PHPobj->title : null;
            $paragraph = isset($PHPobj->paragraph) && $PHPobj->paragraph != "" ? $PHPobj->paragraph : null;
            $postCategory = isset($PHPobj->category) && $PHPobj->category != "" ? $PHPobj->category : null;
            //handle cleaning here
            clean_input($postTitle);
            clean_input($paragraph);
            clean_input($postCategory);
    
            $sql = "SELECT * FROM  tbl_user WHERE username = '$userName' LIMIT 1";

            // $jsonData = $_REQUEST['q'];
            // $PHPobj = json_decode($jsonData);
            // $userName = $PHPobj->title;
            $response['query2'] = $userName ;
            $response['query'] = $_REQUEST['raw_data'];
            $response['title'] = $PHPobj->title;
            $response['message'] = "Captured";
            $response['request'] = $_REQUEST;
    
            if ($result = $conn->query($sql)) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $userId = $row["user_id"];
    
                    $sqlP = "INSERT INTO tbl_post ( post_title ) VALUES ( '$postTitle' )";
                    if ($conn->query($sqlP) === TRUE) {
                        $post_id = $conn->insert_id;

                        $sql1 = "SELECT * FROM tbl_post_category WHERE post_category = '$postCategory'";
                        if ($result1 = $conn->query($sql1)) {
                            // Associative array
                            $row1 = $result1->fetch_assoc(); //$row["post_category_id"]
                            $categoryId = $row1["post_category_id"];
                
                            $sql2 = "INSERT INTO tbl_post_vs_category (post_id, post_category_id) VALUES('$post_id','$categoryId')";
                            if ($conn->query($sql2) === TRUE) {
                                $sql3 = "INSERT INTO tbl_user_vs_post (user_id, post_id) VALUES ('$userId','$post_id')";
                                if ($conn->query($sql3) === TRUE) {
                                    // explode into array
                                    $para_array = preg_split('/[\n\r]+/', $paragraph);
                                    // now, my $array will have all the lines I need
                                    for ($i = 0; $i < count($para_array); $i++) {
                                        $each_para = trim($para_array[$i]);
                                        $sql4 = "INSERT INTO tbl_post_paragraphs (paragraph, post_id) VALUES ('$each_para','$post_id')";
                
                                        if ($conn->query($sql4) === TRUE) {
                                            $response['status'] = "ok";
                                            if ($i == count($para_array) - 1) {
                                                // handle image upload into the folder and db
                                                if (isset($PHPobj->fileToUpload)  && $PHPobj->fileToUpload !== "" ) {
                                                    // check fields have data and handle the situation
                                                    $fileName = isset($PHPobj->fileToUpload) && $PHPobj->fileToUpload != "" ? $PHPobj->fileToUpload : null;
                                                    $sql5 = "INSERT INTO tbl_image (image_file, post_id) VALUES('$fileName', '$post_id')";
                                                    if ($conn->query($sql5) === TRUE) {
                                                        $response['success'] = 1;
                                                        $response['message'] = "New Post created successfully";
                                                    } else {
                                                        $response['message'] = "Query Failed";
                                                    }
                                                } else {
                                                    $response['success'] = 1;
                                                    $response['message'] = "New Post (With no Media) created successfully";
                                                }
                                            }
                                        } else {
                                            $response['status'] = "Error Occurred";
                                            $response['message'] = "Query Failed";
                                            $response['Error'] = $conn->error;
                                            break;
                                        }
                                    }
                                } else {
                                    $response['message'] = "Query Failed";
                                    $response['Error'] = $conn->error;
                                }
                            } else {
                                $response['message'] = "Query Failed";
                                $response['Error'] = $conn->error;
                            }
                        } else {
                            $response['message'] = "Query Failed";
                            $response['Error'] = $conn->error;
                        }
                    } else {   
                        $response['message'] = "Post Not Created";
                        $response['Error'] = $conn->error;
                    }
    
                } else {
                    $response['message'] = "Incorrect username or password";
                }
            } else {
                $response['message'] = "Server didn't respond, please try again!";
            } 
    
        } else {
            $response['message'] = "Username Missing!!";
        }
    } else {
        $response['message'] = "Please fill all fields!!";
    }
    

} else {
    $response['message'] = "You are just unserious. That's All!";
}

echo json_encode($response);


$conn->close();