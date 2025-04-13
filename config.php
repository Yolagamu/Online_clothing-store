<?php
// config.php

// Only start the session if none exists yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection settings
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'yolandas_clothing';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
