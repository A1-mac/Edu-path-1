<?php
// Database connection parameters
$servername = "localhost";
$dbusername = "mac";        // Replace with your MySQL username
$dbpassword = "pass";       // Replace with your MySQL password
$dbname = "Edupath_db";     // The database name

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
