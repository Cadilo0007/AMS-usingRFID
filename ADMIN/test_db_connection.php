<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Database connection successful!";
}
?>
