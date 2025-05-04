<?php
$host = 'localhost'; // or your database host
$user = 'root'; // or your database username
$pass = ''; // or your database password
$db = 'rfidattendance_db'; // your database name

// Establish a database connection using the defined variables
$connection = mysqli_connect($host, $user, $pass, $db);

// Check the connection
if (!$connection) {
    // If the connection fails, stop the script and show the error message
    die("Database connection failed: " . mysqli_connect_error());
}
?>


