<?php

include '../dbConfig.php';
include '../sanitizer.php';
include '../pepperConfig.php'; //$pepper added..
$response['success'] = 0;

if (isset($_REQUEST['id']) && $_REQUEST['id'] !== "") {
    $id = $_REQUEST['id'];
    //handle cleaning here
    clean_input($id);

    $sql = "UPDATE tbl_product SET ";
    $comma = 0;
    $atleast1Param = 0;
    $fieldsCaptured = array();
    
    // Product name
    if (isset($_REQUEST['pdt_name']) && $_REQUEST['pdt_name'] !== "") {
        $pdt_name = $_REQUEST['pdt_name'];
        clean_input($pdt_name);
        if ($comma) {
            $sql .= ", pdt_name = '$pdt_name'";
        } else {
            $sql .= " pdt_name = '$pdt_name'";
        }
        $comma = 1;
        $atleast1Param = 1;
        array_push($fieldsCaptured, "pdt_name");
    }

    // quantity
    if (isset($_REQUEST['quantity']) && $_REQUEST['quantity'] !== "") {
        $quantity = $_REQUEST['quantity'];
        clean_input($quantity);
        if ($comma) {
            $sql .= ", available_quantity = '$quantity'";
        } else {
            $sql .= " available_quantity = '$quantity'";
        }
        $comma = 1;
        $atleast1Param = 1;
        array_push($fieldsCaptured, "quantity");
    } 
    
    // pdt_units
    if (isset($_REQUEST['pdt_units']) && $_REQUEST['pdt_units'] !== "") {
        $pdt_units = $_REQUEST['pdt_units'];
        clean_input($pdt_units);
        if ($comma) {
            $sql .= ", pdt_units = '$pdt_units'";
        } else {
            $sql .= " pdt_units = '$pdt_units'";
            $comma = 1;
        }
        $atleast1Param = 1;
        array_push($fieldsCaptured, "pdt_units");
    } 
    // unit_price
    if (isset($_REQUEST['unit_price']) && $_REQUEST['unit_price'] !== "") {
        $unit_price = $_REQUEST['unit_price'];
        clean_input($unit_price);
        if ($comma) {
            $sql .= ", unit_price = '$unit_price'";
        } else {
            $sql .= " unit_price = '$unit_price'";
            $comma = 1;
        }
        $atleast1Param = 1;
        array_push($fieldsCaptured, "unit_price");
    } 
  
    // isAvailable
    if (isset($_REQUEST['isAvailable']) && $_REQUEST['isAvailable'] !== "") {
        $isAvailable = $_REQUEST['isAvailable'];
        clean_input($isAvailable);
        if ($comma) {
            $sql .= ", isAvailable = '$isAvailable'";
        } else {
            $sql .= " isAvailable = '$isAvailable'";
            $comma = 1;
        }
        $atleast1Param = 1;
        array_push($fieldsCaptured, "isAvailable");
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
    
    
    // Now, firing of the sql statement in complete format required
    if ($atleast1Param) {
        $sql .= " WHERE product_id = '$id'";

        if ($conn->query($sql) === TRUE) {
            $response['success'] = 1;
            $response['message'] = "Product updated successfully";
            $response['fieldsCaptured'] = $fieldsCaptured;
        } else {
            $response['fieldsCaptured'] = $fieldsCaptured;
            $response['message'] = "Error updating Product: " . $conn->error;
        }
    } else {
        $response['message'] = "Please select a field to update!";
    }
    
} else {
    $response['message'] = "Please select Product to update!";
}

echo json_encode($response);

$conn->close();
