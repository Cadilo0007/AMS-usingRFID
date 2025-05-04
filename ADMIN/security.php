<?php
session_start();

// Include your database configuration file
include('database/dbconfig.php');

// Check if the database connection is established
if (!$connection) {
    // Handle the error if the database connection fails
    die("Database connection failed.");
}

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['username'])) {

    header('Location: login.php');
    exit(); 
}
?>





