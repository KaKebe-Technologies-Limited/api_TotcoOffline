<?php

include '../dbConfig.php';
include '../sanitizer.php';

$response['success'] = 0;

if (isset($_REQUEST['product_name'])  && isset($_REQUEST['unit_price'])  && $_REQUEST['product_name'] !== "" && $_REQUEST['unit_price'] !== "") {
    if (isset($_REQUEST['createdBy'])  && $_REQUEST['createdBy'] !== "") {
        $product_name = $_REQUEST['product_name'];
        $unit_price = $_REQUEST['unit_price'];
        $createdBy = $_REQUEST['createdBy'];
        $units = isset($_REQUEST['units'])  && $_REQUEST['units'] !== "" ? $_REQUEST['units'] : null;
    
        //handle cleanning here
        clean_input($product_name);
        clean_input($unit_price);
        clean_input($createdBy);
        clean_input($units);

        //check if product_name already exists
        $sql1 = "SELECT pdt_name FROM tbl_product WHERE pdt_name = '$product_name' LIMIT 1";
        $result = $conn->query($sql1);
    
        if ($result->num_rows > 0) {
            //at this stage, we got a result from db, meaning: product_name already exists!
            $response['message'] = "Product Name already exists!";
        } else {
            $sql = "INSERT INTO tbl_product (pdt_name, unit_price, pdt_units, createdBy) VALUES ('$product_name', '$unit_price', '$units', '$createdBy')";
        
            if ($conn->query($sql)) {
                $response['success'] = 1;
                $response['message'] = "Product created successfully";
            } else {
                $response['message'] = "Error creating Product: " . $conn->error;
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

