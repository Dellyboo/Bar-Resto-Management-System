<?php
session_start();

// Clear all session variables
$_SESSION = array();

// If using cookies, clear the session cookie as well
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session data
session_destroy();

// Prevent browser cache for the login page (no caching)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login page
header('Location: login/login.php');
exit;
?>
