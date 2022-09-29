<?php

include '../dbConfig.php';
include '../sanitizer.php';

$response['success'] = 0;

if (isset($_REQUEST['branch_name'])  && isset($_REQUEST['location'])  && $_REQUEST['branch_name'] !== "" && $_REQUEST['location'] !== "") {
    if (isset($_REQUEST['createdBy'])  && $_REQUEST['createdBy'] !== "") {
        $branch_name = $_REQUEST['branch_name'];
        $location = $_REQUEST['location'];
        $createdBy = $_REQUEST['createdBy'];
        $manager = isset($_REQUEST['manager'])  && $_REQUEST['manager'] !== "" ? $_REQUEST['manager'] : null;
    
        //handle cleanning here
        clean_input($branch_name);
        clean_input($location);
        clean_input($createdBy);
        clean_input($manager);

        //check if branch_name already exists
        $sql1 = "SELECT branch_name FROM  tbl_branch WHERE branch_name = '$branch_name' LIMIT 1";
        $result = $conn->query($sql1);
    
        if ($result->num_rows > 0) {
            //at this stage, we got a result from db, meaning: branch_name already exists!
            $response['message'] = "Branch Name already exists!";
        } else {
            $sql = "INSERT INTO tbl_branch (branch_name, location, createdBy, branch_manager) VALUES ('$branch_name', '$location', '$createdBy', '$manager')";
        
            if ($conn->query($sql)) {
                $response['success'] = 1;
                $response['message'] = "New Branch created successfully";
            } else {
                $response['message'] = "Error creating Branch: " . $conn->error;
            }
        } 
    } else {
        $response['message'] = "Please Login and try again.";
    }
} else {
    $response['message'] = "Please fill in all Fields!";
}

echo json_encode($response);

$conn->close();

