<?php

include '../dbConfig.php';
include '../sanitizer.php';
include '../pepperConfig.php'; //$pepper added..

$sql = "SELECT * FROM tbl_user";

// $result = $conn->query($sql);
$urlToImages = "http://localhost/APIs/apiCampusWeekly/api/lukard/images/";
if ($result = $conn->query($sql)) {

    if ($result->num_rows > 0) {
        $response['success'] = 1;
        $response['status'] = "ok";
        $getdata = array();

        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($getdata, $row);
        }

        $newDataWithImageUrl = array();
        $userCredentialsArray = array();

        for ($x = 0; $x < count($getdata); $x++) {

            $JsonEconded = json_encode($getdata[$x]); // encode to make JSON object
            $obj = json_decode($JsonEconded); // decode to change JSON object into a PHP object 

            $imageURL = $urlToImages . $obj->profile_photo; // attaching the url to the image_file

            $userCredentialsArray['user_id'] = $obj->user_id; //
            $userCredentialsArray['user_type'] = $obj->isAdmin == 1 ? "ADMIN" : "USER";
            $userCredentialsArray['account_approved'] = $obj->isAgent == 1 ? "APPROVED" : null;
            $userCredentialsArray['isAdmin'] = $obj->isAdmin == 1  ? "true" : null;
            $userCredentialsArray['isAgent'] = $obj->isAgent == 1  ? "true" : null;
            $userCredentialsArray['isSystemAdmin'] = $obj->isSystemAdmin == 1  ? "true" : null;
            
            $UC_Econded = json_encode($userCredentialsArray); // to JSON object
            $UC_Obj = json_decode($UC_Econded); // to PHP object 

            $objToArray['user_credentials'] = $UC_Obj; // user credentials object added to the array

            $objToArray['firstname'] = $obj->firstname ? $obj->firstname : null;
            $objToArray['lastname'] = $obj->lastname ? $obj->lastname : null;
            $objToArray['username'] = $obj->username ? $obj->username : null;
            $objToArray['email'] = $obj->email ? $obj->email : "--";
            $objToArray['phone_number'] = $obj->phone_number ? $obj->phone_number : "--";

            $objToArray['urlToImage'] = $obj->profile_photo ? $imageURL : null;
            $objToArray['created_at'] = $obj->createdAt;
            $objToArray['updated_at'] = $obj->updatedAt ? $obj->updatedAt : null;

            array_push($newDataWithImageUrl, $objToArray);
        }

        $response['totalCount'] = count($newDataWithImageUrl);
        $response['users'] = $newDataWithImageUrl;
    } else {
        $response['success'] = 0;
        $response['status'] = "0 results";
        $response['message'] = "Currently, 0 users in the system";
    }
} else {
    $response['success'] = 0;
    $response['status'] = "0 results";
    $response['message'] = "Server didn't respond, please try again!";
}

echo json_encode($response);

$conn->close();

