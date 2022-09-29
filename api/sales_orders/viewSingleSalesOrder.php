<?php

include '../dbConfig.php';
include '../sanitizer.php';

$response['success'] = 0;

// $sales_order_id;

if (isset($_REQUEST['id'])  && $_REQUEST['id'] !== "") {
    $id = clean_input($_REQUEST['id']);

    $sql1 = "SELECT * FROM tbl_sales_order WHERE sales_order_id = '$id' AND isDeleted = '0' LIMIT 1";

    $sql = "SELECT op.order_vs_pdt_id, op.sales_order_id, op.product_id, op.quantity, p.pdt_name FROM tbl_order_vs_product op JOIN tbl_sales_order o ON op.sales_order_id = o.sales_order_id JOIN tbl_product p ON op.product_id = p.product_id WHERE op.sales_order_id = '$id' ORDER BY op.order_vs_pdt_id DESC";

    if ($result1 = $conn->query($sql1)) {
    
        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_assoc();
            $response['order_status'] = $row1;

            // Now, get the products under this sales order;
            if ($result = $conn->query($sql)) {
                if ($result->num_rows > 0) {
                    $response['success'] = 1;  
                    $getdata = array();
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        array_push($getdata, $row);
                    } 
                    $response['totalItems'] = count($getdata);
                    $response['order_items'] = $getdata;
                } else {
                    $response['message'] = "Sales Order with id: $id not found.  ";
                }
            } else {
                $response['status'] = "0 results";
                $response['message'] = "Server didn't respond, please try again!";
            }  
        } else {
            $response['message'] = "Sales Order with id: $id not found.  ";
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
