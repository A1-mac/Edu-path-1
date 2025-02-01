<?php
// Database connection
$host = 'localhost';
$username = 'mac';
$password = 'pass';
$dbname = 'Edupath_db';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the CSV file
$csvFile = 'universities.csv'; // Make sure the CSV is in the same directory
if (!file_exists($csvFile)) {
    die("CSV file not found. Please run the Python script to request the data.");
}

$file = fopen($csvFile, 'r');

// Skip the header row
fgetcsv($file);

// Prepare SQL query with a duplication check
$query = "INSERT INTO universities (sn, name, head_office, type, status, action)
          SELECT ?, ?, ?, ?, ?, ?
          WHERE NOT EXISTS (
              SELECT 1 FROM universities WHERE name = ? AND head_office = ?
          )";
$stmt = $conn->prepare($query);

// Loop through CSV rows
while (($row = fgetcsv($file)) !== false) {
    $sn = $row[0];
    $name = $row[1];
    $head_office = $row[2];
    $uni_type = $row[3];
    $status = $row[4];
    $action = $row[5];
    
    // Bind parameters (including the duplication check fields)
    $stmt->bind_param("isssssss", $sn, $name, $head_office, $uni_type, $status, $action, $name, $head_office);
    $stmt->execute();
}

// Close resources
fclose($file);
$stmt->close();
$conn->close();

// Delete the CSV file to avoid reprocessing
unlink($csvFile);

// Redirect back to the dashboard
header("Location: dash.php");
exit;
?>
