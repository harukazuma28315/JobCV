<?php

$host = "localhost";
$dbname = "testdatabase";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_errno) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>