<?php

include '../dbConfig.php';

$response['success'] = 0;

$sql = "SELECT * FROM tbl_product";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        $getdata = array();
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($getdata, $row);
        }

        $response['success'] = 1;
        $response['totalResults'] = count($getdata);
        $response['products'] = $getdata;
        
    } else {
        $response['status'] = "0 results";
        $response['message'] = "0 results";
    }
} else {
    $response['message'] = "Server didn't respond, please try again!";
}

echo json_encode($response);


$conn->close();
