<?php
session_start();

// Clear all session data
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: employeelogin.php");
exit();
?>
