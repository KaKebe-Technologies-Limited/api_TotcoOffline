<?php

include '../dbConfig.php';
include '../sanitizer.php';
include '../pepperConfig.php'; //$pepper added..
$response['success'] = 0;

if (isset($_REQUEST['id']) && $_REQUEST['id'] !== "") {
    $id = $_REQUEST['id'];
    //handle cleaning here
    clean_input($id);

    $sql = "UPDATE tbl_branch SET ";
    $comma = 0;
    $atleast1Param = 0;
    $fieldsCaptured = array();
    
    // branch_name
    if (isset($_REQUEST['branch_name']) && $_REQUEST['branch_name'] !== "") {
        $branch_name = $_REQUEST['branch_name'];
        clean_input($branch_name);
        if ($comma) {
            $sql .= ", branch_name = '$branch_name'";
        } else {
            $sql .= " branch_name = '$branch_name'";
        }
        $comma = 1;
        $atleast1Param = 1;
        array_push($fieldsCaptured, "branch_name");
    }

    // location
    if (isset($_REQUEST['location']) && $_REQUEST['location'] !== "") {
        $location = $_REQUEST['location'];
        clean_input($location);
        if ($comma) {
            $sql .= ", location = '$location'";
        } else {
            $sql .= " location = '$location'";
        }
        $comma = 1;
        $atleast1Param = 1;
        array_push($fieldsCaptured, "location");
    } 
    
    // branch_manager
    if (isset($_REQUEST['branch_manager']) && $_REQUEST['branch_manager'] !== "") {
        $branch_manager = $_REQUEST['branch_manager'];
        clean_input($branch_manager);
        if ($comma) {
            $sql .= ", branch_manager = '$branch_manager'";
        } else {
            $sql .= " branch_manager = '$branch_manager'";
            $comma = 1;
        }
        $atleast1Param = 1;
        array_push($fieldsCaptured, "branch_manager");
    } 

    
    // Now, firing of the sql statement in complete format required
    if ($atleast1Param) {
        $sql .= " WHERE branch_id = '$id'";

        if ($conn->query($sql) === TRUE) {
            $response['success'] = 1;
            $response['message'] = "Branch updated successfully";
            $response['fieldsCaptured'] = $fieldsCaptured;
        } else {
            $response['fieldsCaptured'] = $fieldsCaptured;
            $response['message'] = "Error updating Branch: " . $conn->error;
        }
    } else {
        $response['message'] = "Please select a field to update!";
    }
    
} else {
    $response['message'] = "Please select Branch to update!";
}

echo json_encode($response);

$conn->close();
