<?php

include '../dbConfig.php';
include '../sanitizer.php';
include '../pepperConfig.php'; //$pepper added..

if (isset($_REQUEST['user_name']) || isset($_REQUEST['user_id'])) {
  if (isset($_REQUEST['user_name']) && $_REQUEST['user_name'] !== "") {
    $userName = $_REQUEST['user_name'];
    //handle cleanning here
    clean_input($userName);

    $sql = "DELETE FROM tbl_user WHERE username = '$userName'";

    if ($conn->query($sql) === TRUE) {
      $response['success'] = 1;
      $response['message'] = "User deleted successfully";
    } else {
      $response['success'] = 0;
      $response['message'] = "Error deleting user: " . $conn->error;
    }
  } elseif (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] !== "") {

    $user_id = $_REQUEST['user_id'];
    //handle cleanning here
    clean_input($user_id);

    $sql = "DELETE FROM tbl_user WHERE user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
      $response['success'] = 1;
      $response['message'] = "User deleted successfully";
    } else {
      $response['success'] = 0;
      $response['message'] = "Error deleting user: " . $conn->error;
    }
  } else {
    $response['success'] = 0;
    $response['message'] = "Confirm user to delete!";
  }
} else {
  $response['success'] = 0;
  $response['message'] = "Select user to delete";
}

echo json_encode($response);


$conn->close();
