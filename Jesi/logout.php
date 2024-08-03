<?php
// Start the session
session_start();

// Destroy the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Optionally, clear cookies if they were set
if (isset($_COOKIE['email'])) {
    setcookie('email', '', time() - 3600, '/'); // Expire the cookie
}
if (isset($_COOKIE['password'])) {
    setcookie('password', '', time() - 3600, '/'); // Expire the cookie
}

// Redirect to the login page or any other page
header('Location: index.php');
exit();
?>
