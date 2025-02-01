<?php
// Database connection
$host = 'localhost';
$username = 'mac';
$password = 'pass';
$dbname = 'Edupath_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$csvFile = 'university_Logbook.csv';

if (!file_exists($csvFile)) {
    die("CSV file not found.");
}

$file = fopen($csvFile, 'r');
fgetcsv($file); // Skip header

if (isset($tuition_fees) && !empty($tuition_fees)) {
    $tuition_fees = preg_replace('/[^0-9.]/', '', $tuition_fees); // Remove non-numeric characters
    $tuition_fees = (float) $tuition_fees; // Convert to decimal
}

// Prepare SQL query
$query = "INSERT INTO courses (
            university_name, 
            program_name, 
            admission_requirements, 
            program_duration, 
            admission_capacity, 
            tuition_fees
          ) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

while (($row = fgetcsv($file)) !== false) {
    // Map CSV columns
    $university_name = $conn->real_escape_string($row[0]);
    $program_name = $conn->real_escape_string($row[1]);
    $admission_requirements = $conn->real_escape_string($row[2]);
    $program_duration = is_numeric($row[3]) ? $row[3] : 0;
    $admission_capacity = (int)$row[4];
    $tuition_fees = $conn->real_escape_string($row[5]);

    // Verify university exists
    $check_query = "SELECT name FROM universities WHERE name = '$university_name'";
    $result = $conn->query($check_query);
    
    if ($result->num_rows === 0) {
        echo "University '$university_name' not found. Skipping.<br>";
        continue;
    }

    // Bind and execute
    $stmt->bind_param(
        "ssssis",
        $university_name,
        $program_name,
        $admission_requirements,
        $program_duration,
        $admission_capacity,
        $tuition_fees
    );

    if (!$stmt->execute()) {
        echo "Error inserting record: " . $stmt->error . "<br>";
    }
}

fclose($file);
$stmt->close();
$conn->close();

echo "Courses successfully uploaded to the database.";
?>