<?php
session_start();
//session_unset(); // Clear all session variables
//session_destroy(); // Destroy the session
//header('Location: login.php'); // Redirect to login page
//exit();

// Clear session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();
?>
