<?php
session_start();
// Unset all of the session variables
$_SESSION = [];

// Destroy the session.
session_destroy();

// Redirect to Sign Up page
header("Location: login.php");
exit;
