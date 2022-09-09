<?php

include '../dbConfig.php';
include '../sanitizer.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");


if ( (isset($_REQUEST['createdBy']) && $_REQUEST['createdBy'] !== ""  ) && (isset($_REQUEST['raw_data']) && $_REQUEST['raw_data'] !== "" ) ) {
    // Access and decode the json data send by post
    $jsonData = $_REQUEST['raw_data'];
    $PHPobj = json_decode($jsonData);
    $total = count((array)$PHPobj); // to get length of object in php

    //handle cleaning here
    $createdBy = clean_input($_REQUEST['createdBy']);        
    
    if ((isset($PHPobj->product_id) && $PHPobj->product_id != "") ) {
        
        // check fields have data and handle the situation
        $product_id =  $PHPobj->product_id;
        $quantity = isset($PHPobj->quantity)  && $PHPobj->quantity != "" ? $PHPobj->quantity : null;
        $selling_price = isset($PHPobj->selling_price)  && $PHPobj->selling_price != "" ? $PHPobj->selling_price : null;
        

        //handle cleaning here
        clean_input($product_id);
        clean_input($quantity);
        clean_input($selling_price);
    }

       
            


    $sql = "INSERT INTO tbl_sales_order ( createdBy ) VALUES ('$createdBy')";

    if ($conn->query($sql) === TRUE) {
        $sales_order_id = $conn->insert_id;
        $sql1 = "SELECT * FROM tbl_post_category WHERE post_category = '$postCategory'";

        if ($result = $conn->query($sql1)) {
            // Associative array
            $row = $result->fetch_assoc(); //$row["post_category_id"]
            $categoryId = $row["post_category_id"];

            $sql2 = "INSERT INTO tbl_post_vs_category (sales_order_id, post_category_id) VALUES('$sales_order_id','$categoryId')";
            if ($conn->query($sql2) === TRUE) {
                $sql3 = "INSERT INTO tbl_user_vs_post (user_id, sales_order_id) VALUES ('$userId','$sales_order_id')";
                if ($conn->query($sql3) === TRUE) {
                    // explode into array
                    $para_array = preg_split('/[\n\r]+/', $paragraph);
                    // now, my $array will have all the lines I need
                    for ($i = 0; $i < count($para_array); $i++) {
                        $each_para = trim($para_array[$i]);
                        $sql4 = "INSERT INTO tbl_post_paragraphs (paragraph, sales_order_id) VALUES ('$each_para','$sales_order_id')";

                        if ($conn->query($sql4) === TRUE) {
                            $response['status'] = "ok";
                            if ($i == count($para_array) - 1) {
                                // image upload into the folder and db
                                $sql5 = "INSERT INTO tbl_image (image_file, sales_order_id) VALUES('$fileName', '$sales_order_id')";
                                if ($conn->query($sql5) === TRUE) {
                                    $response['success'] = 1;
                                    $response['message'] = "New Post created successfully";
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = "Query Failed";
                                }
                            }
                        } else {
                            $response['status'] = "Error Occurred";
                            $response['success'] = 0;
                            $response['message'] = "Query Failed";
                            $response['Error'] = $conn->error;
                            break;
                        }
                    }
                } else {
                    $response['success'] = 0;
                    $response['message'] = "Query Failed";
                    $response['Error'] = $conn->error;
                }
            } else {
                $response['success'] = 0;
                $response['message'] = "Query Failed";
                $response['Error'] = $conn->error;
            }
        } else {
            $response['success'] = 0;
            $response['message'] = "Query Failed";
            $response['Error'] = $conn->error;
        }
    } else {
        $response['success'] = 0;
        $response['message'] = "Post Not Created";
        $response['Error'] = $conn->error;
    }
 
} else {
    $response['success'] = 0;
    $response['message'] = "You're not Serious!";
}

echo json_encode($response);


$conn->close();