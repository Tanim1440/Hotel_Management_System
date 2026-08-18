<?php

$host = "localhost";
$username = "root";
$password = "mysqltanim520";
$database = "hotel_management_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


?>