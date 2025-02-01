<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include('conn.php');

// Fetch the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Initialize error messages
$fullnameError = $emailError = $phoneError = "";
$successMessage = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));

    $hasError = false;

    // Full Name Validation: Only letters and spaces
    if (empty($name)) {
        $fullnameError = "Full Name is required.";
        $hasError = true;
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $fullnameError = "Full Name must contain only letters and spaces.";
        $hasError = true;
    }

    // Check for valid email
    if (empty($email)) {
        $emailError = "Email is required.";
        $hasError = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format.";
        $hasError = true;
    }

    // Phone Validation: Only numbers allowed and minimum 10 digits
    if (empty($phone)) {
        $phoneError = "Phone number is required.";
        $hasError = true;
    } elseif (!preg_match("/^[0-9]{10,}$/", $phone)) {
        $phoneError = "Phone number must contain only numbers and be at least 10 digits long.";
        $hasError = true;
    }

    // If no errors, proceed to update the database
    if (!$hasError) {
        // Prepare the SQL query to update user details
        $query = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $phone, $user_id);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect with a success message
            $_SESSION['successMessage'] = "Profile updated successfully!";
            header("Location: home.php");
        } else {
            $_SESSION['errorMessage'] = "Failed to update your profile. Please try again.";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>