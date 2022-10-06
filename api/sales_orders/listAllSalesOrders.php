<?php

include '../dbConfig.php';
include '../sanitizer.php';

$response['success'] = 0;

$sql1 = "SELECT o.* , u.user_id, u.username Agent_Name, u.firstname, u.lastname
FROM tbl_sales_order o
JOIN tbl_user u ON o.createdBy= u.user_id
WHERE isDeleted = '0'";

$response_array = array();

if ($result1 = $conn->query($sql1)) {
    
    if ($result1->num_rows > 0) {
        $order_status = array();
        // output data of each row
        while ($row1 = $result1->fetch_assoc()) { // start while loop
            // array_push($order_status, $row1);
            $singleOrder_array['order_status'] =  $row1;
            // so, for each row ---- $row1["sales_order_id"]
            $row_id = $row1["sales_order_id"];

            $sql = "SELECT op.order_vs_pdt_id, op.sales_order_id, op.product_id, op.quantity, p.pdt_name
            FROM tbl_order_vs_product op 
            JOIN tbl_sales_order o ON op.sales_order_id = o.sales_order_id 
            JOIN tbl_product p ON op.product_id = p.product_id 
             
            WHERE op.sales_order_id = '$row_id' 
            ORDER BY op.order_vs_pdt_id 
            DESC";

            // Now, get the products under this sales order;
            if ($result = $conn->query($sql)) {
                if ($result->num_rows > 0) {
                    $response['success'] = 1;  
                    $getdata = array();
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        array_push($getdata, $row);
                    } 
                    
                    $singleOrder_array['order_items'] = $getdata;
                    array_push($response_array, $singleOrder_array);
                }
                
            } else {
                $response['status'] = "0 results";
                $response['message'] = "Server didn't respond, please try again!";
            }  
        } // end while loop
        // $response['order_status'] = $order_status;
        $response['orders'] = $response_array;

    } else {
        $response['message'] = "Sales Order with id: $id not found.  ";
    }
} else {
    $response['status'] = "0 results";
    $response['message'] = "Server didn't respond, please try again!";
}

echo json_encode($response);

$conn->close();
