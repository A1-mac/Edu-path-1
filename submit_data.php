<?php

// Include the database connection
include('conn.php');

// Start session to access user_id
session_start();

// Redirect to login if session is not set
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Read JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

// Validate received data
if (!isset($data["examination_number"], $data["year_of_exam"], $data["exam_type"], $data["gender"], $data["division"], $data["points"], $data["subjects"])) {
    echo "Error: Missing required fields";
    exit;
}

// Map gender short form to long form
$gender = strtoupper($data["gender"]);
$gender_long_form = ($gender === "M") ? "Male" : (($gender === "F") ? "Female" : "Unknown");

// Prepare and execute the SQL query to insert results
$stmt = $conn->prepare("INSERT INTO student_results (user_id, examination_number, year_of_exam, exam_type, gender, division, points) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssss", $user_id, $data["examination_number"], $data["year_of_exam"], $data["exam_type"], $gender_long_form, $data["division"], $data["points"]);

if ($stmt->execute()) {
    // Get last inserted student ID
    $student_id = $stmt->insert_id;
    
    // Insert subjects into the subjects table and link them with the student_id
    $stmt_subjects = $conn->prepare("INSERT INTO student_subjects (student_id, subject_name, score) VALUES (?, ?, ?)");
    
    foreach ($data["subjects"] as $subject => $score) {
        $stmt_subjects->bind_param("iss", $student_id, $subject, $score);
        $stmt_subjects->execute();
    }
    
    echo "Success: Data submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close statements and connection
$stmt->close();
$stmt_subjects->close();
$conn->close();
?>
