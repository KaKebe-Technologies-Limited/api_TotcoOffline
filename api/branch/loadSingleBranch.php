<?php

include '../dbConfig.php';
include '../sanitizer.php';

$response['success'] = 0;

// $sales_order_id;

if (isset($_REQUEST['id'])  && $_REQUEST['id'] !== "") {
    $id = clean_input($_REQUEST['id']);

    $sql = "SELECT * FROM  tbl_branch WHERE branch_id = '$id' LIMIT 1";
   
    if ($result = $conn->query($sql)) {
    
        if ($result->num_rows > 0) {
            $response['success'] = 1;    
            $row = $result->fetch_assoc();
            
            $response['branch_data'] = $row;
        } else {
            $response['message'] = "Branch with id: $id not found.  ";
        }
    } else {
        $response['status'] = "0 results";
        $response['message'] = "Server didn't respond, please try again!";
    }

} else {
    $response['message'] = "Please select the Branch whose details you wish to view.";
}

echo json_encode($response);

$conn->close();
