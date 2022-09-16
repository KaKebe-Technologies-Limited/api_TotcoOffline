<?php

include '../dbConfig.php';
include '../sanitizer.php';

$response['success'] = 0;

// $sales_order_id;

if (isset($_REQUEST['id'])  && $_REQUEST['id'] !== "") {
    $sales_order_id = clean_input($_REQUEST['id']);
    
    $sql = "SELECT op.order_vs_pdt_id, op.sales_order_id, op.product_id, op.quantity, p.pdt_name FROM tbl_order_vs_product op JOIN tbl_sales_order o ON op.sales_order_id = o.sales_order_id JOIN tbl_product p ON op.product_id = p.product_id WHERE op.sales_order_id = '$sales_order_id' ORDER BY op.order_vs_pdt_id DESC";

    if ($result = $conn->query($sql)) {
    
        if ($result->num_rows > 0) {
            $response['success'] = 1;    
            $row = $result->fetch_assoc();
            
            $response['order_detail'] = $row;
        } else {
            $response['message'] = "Sales Order with id: $sales_order_id not found.  ";
        }
    } else {
        $response['status'] = "0 results";
        $response['message'] = "Server didn't respond, please try again!";
    }

} else {
    $response['message'] = "Please select the sales order to view.";
}

echo json_encode($response);

$conn->close();
