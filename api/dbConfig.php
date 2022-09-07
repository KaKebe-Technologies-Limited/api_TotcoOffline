<?php

$HOST = "localhost";
$USER = "root";
$PASSWORD = "";
$DB_NAME = "campusgf_totcodb";

// Create connection
$conn = new mysqli($HOST, $USER, $PASSWORD, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
