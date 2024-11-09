<?php
// connect.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection code goes here
$servername = "moviealtodb.czqg8ymmaajc.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "moviealto123"; // Your database password
$dbname = "moviealtoulti"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
