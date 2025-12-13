<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../html/login.html"); // ✅ Update the path based on your folder
exit;
?>
