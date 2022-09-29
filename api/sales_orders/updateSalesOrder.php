<?php

include '../dbConfig.php';
include '../sanitizer.php';
include '../pepperConfig.php'; //$pepper added..
$response['success'] = 0;

if (isset($_REQUEST['id']) && $_REQUEST['id'] !== "") {
    $id = $_REQUEST['id'];
    //handle cleaning here
    clean_input($id);

    $sql = "UPDATE tbl_sales_order SET ";
    $comma = 0;
    $atleast1Param = 0;
    $fieldsCaptured = array();
  
    // isApproved 
    if (isset($_REQUEST['isApproved']) && $_REQUEST['isApproved'] !== "") {
        $isApproved = $_REQUEST['isApproved'];
        clean_input($isApproved);
        if ($comma) {
            $sql .= ", isApproved = '$isApproved'";
        } else {
            $sql .= " isApproved = '$isApproved'";
            $comma = 1;
        }
        $atleast1Param = 1;
        array_push($fieldsCaptured, "isApproved");
    } 

    // approvedBy  
    if (isset($_REQUEST['approvedBy']) && $_REQUEST['approvedBy'] !== "") {
        $approvedBy = $_REQUEST['approvedBy'];
        clean_input($approvedBy);
        if ($comma) {
            $sql .= ", approvedBy = '$approvedBy'";
        } else {
            $sql .= " approvedBy = '$approvedBy'";
            $comma = 1;
        }
        $atleast1Param = 1; 
        array_push($fieldsCaptured, "approvedBy");      
    } 

    // isDeleted  
    if (isset($_REQUEST['isDeleted']) && $_REQUEST['isDeleted'] !== "") {
        $isDeleted = $_REQUEST['isDeleted'];
        clean_input($isDeleted);
        if ($comma) {
            $sql .= ", isDeleted = '$isDeleted'";
        } else {
            $sql .= " isDeleted = '$isDeleted'";
            $comma = 1;
        }
        $atleast1Param = 1; 
        array_push($fieldsCaptured, "isDeleted");      
    } 

    // isCancelled  
    if (isset($_REQUEST['isCancelled']) && $_REQUEST['isCancelled'] !== "") {
        $isCancelled = $_REQUEST['isCancelled'];
        clean_input($isCancelled);
        if ($comma) {
            $sql .= ", isCancelled = '$isCancelled'";
        } else {
            $sql .= " isCancelled = '$isCancelled'";
            $comma = 1;
        }
        $atleast1Param = 1; 
        array_push($fieldsCaptured, "isCancelled");      
    } 

    // isPending  
    if (isset($_REQUEST['isPending']) && $_REQUEST['isPending'] !== "") {
        $isPending = $_REQUEST['isPending'];
        clean_input($isPending);
        if ($comma) {
            $sql .= ", isPending = '$isPending'";
        } else {
            $sql .= " isPending = '$isPending'";
            $comma = 1;
        }
        $atleast1Param = 1; 
        array_push($fieldsCaptured, "isPending");      
    } 

    // isRejected  
    if (isset($_REQUEST['isRejected']) && $_REQUEST['isRejected'] !== "") {
        $isRejected = $_REQUEST['isRejected'];
        clean_input($isRejected);
        if ($comma) {
            $sql .= ", isRejected = '$isRejected'";
        } else {
            $sql .= " isRejected = '$isRejected'";
            $comma = 1;
        }
        $atleast1Param = 1; 
        array_push($fieldsCaptured, "isRejected");      
    } 
    
    
    // Now, firing of the sql statement in complete format required
    if ($atleast1Param) {
        $sql .= " WHERE sales_order_id = '$id'";

        if ($conn->query($sql) === TRUE) {
            $response['success'] = 1;
            $response['message'] = "Sales Order updated successfully";
            $response['fieldsCaptured'] = $fieldsCaptured;
        } else {
            $response['fieldsCaptured'] = $fieldsCaptured;
            $response['message'] = "Error updating Sales Order: " . $conn->error;
        }
    } else {
        $response['message'] = "Please select a field to update!";
    }
    
} else {
    $response['message'] = "Please select Sales Order to update!";
}

echo json_encode($response);

$conn->close();
