<?php

include '../dbConfig.php';
include '../sanitizer.php';
include '../pepperConfig.php'; //$pepper added..
$response['success'] = 0;

if (isset($_REQUEST['id']) && $_REQUEST['id'] !== "") {
    $id = $_REQUEST['id'];
    //handle cleaning here
    clean_input($id);
    //check if branch_id already exists
    $sql1 = "SELECT branch_id FROM tbl_branch WHERE branch_id = '$id' AND isDeleted = '1' LIMIT 1";
    $result = $conn->query($sql1);

    if ($result->num_rows > 0) {
        //at this stage, we got a result from db, meaning: product_name already exists!
        $response['message'] = "Branch already deleted!";
    } else {
        $sql = "UPDATE tbl_branch SET isDeleted = '1' WHERE branch_id = '$id'";
    
        if ($conn->query($sql) === TRUE) {
          $response['success'] = 1;
          $response['message'] = "Branch with id: $id deleted successfully";
        } else {
          $response['message'] = "Error deleting Branch: " . $conn->error;
        }
    }
} else {
  $response['message'] = "Select Branch to delete";
}
echo json_encode($response);

$conn->close();
