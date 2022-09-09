<?php

include '../dbConfig.php';
include '../sanitizer.php';

$response['success'] = 0;

$sql = "SELECT op.tbl_id, op.sales_order_id, op.product_id, op.quantity, p.pdt_name FROM tbl_order_vs_product op JOIN tbl_sales_order o ON op.sales_order_id = o.sales_order_id JOIN tbl_product p ON op.product_id = p.product_id ORDER BY op.tbl_id DESC";

if ($result = $conn->query($sql)) {

    if ($result->num_rows > 0) {
        $response['success'] = 1;
        $response['status'] = "ok";
        $getdata = array();

        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($getdata, $row);
        }

        $response['totalCount'] = count($getdata);
        $response['orders'] = $getdata;
    } else {
        $response['status'] = "0 results";
        $response['message'] = "Currently, 0 users in the system";
    }
} else {
    $response['status'] = "0 results";
    $response['message'] = "Server didn't respond, please try again!";
}

echo json_encode($response);

$conn->close();
