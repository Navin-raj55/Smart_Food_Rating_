<?php
// Database connection configuration
$servername = "localhost"; // Change this to your server name if it's not localhost
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "sfr"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
