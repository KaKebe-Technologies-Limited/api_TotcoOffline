<?php

include '../dbConfig.php';
include '../sanitizer.php';
include '../pepperConfig.php'; //$pepper added..

if ((isset($_REQUEST['user_name']) && $_REQUEST['user_name'] !== "") && (isset($_REQUEST['password']) && $_REQUEST['password'] !== "") ) {
    $username = $_REQUEST['user_name'];
    $password = $_REQUEST['password'];
    //handle cleanning here
    clean_input($username);
    clean_input($password);

    $sql = "SELECT * FROM tbl_user WHERE username = '$username'";

    // $result = $conn->query($sql);
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            // Associative array
            $row = $result->fetch_assoc();

            $pwd_peppered = hash_hmac("sha256", $password, $pepper);

            if (password_verify($pwd_peppered, $row["password"])) {
                $response['success'] = 1;
                $response['message'] = "Login successful";
                $response['data'] = $row;
            } else {
                $response['success'] = 0;
                $response['message'] = "Incorrect username or password";
            }
        } else {
            $response['success'] = 0;
            $response['message'] = "Incorrect username or password";
        }
    } else {
        $response['success'] = 0;
        $response['message'] = "Server didn't respond, please try again!";
    }
} else {
    $response['success'] = 0;
    $response['message'] = "Please fill in all fields!";
}

echo json_encode($response);


$conn->close();
