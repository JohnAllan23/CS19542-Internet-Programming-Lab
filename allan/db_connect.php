<?php
// db_connect.php

// Database configuration
$servername = "localhost"; // Replace with your server name if not using localhost
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "movie_review_db"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8
$conn->set_charset("utf8");

// Optional: Set timezone
date_default_timezone_set("America/New_York"); // Change as needed

?>
