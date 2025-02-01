<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include('conn.php');

// Fetch the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Check if a file was uploaded
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    // Get file details
    $fileTmpPath = $_FILES['profile_image']['tmp_name'];
    $fileName = $_FILES['profile_image']['name'];
    $fileSize = $_FILES['profile_image']['size'];
    $fileType = $_FILES['profile_image']['type'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Set allowed file extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Validate file extension
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
        exit();
    }

    // Generate a unique name for the file and set the upload directory
    $newFileName = "user_" . $user_id . "_" . time() . "." . $fileExtension;
    $uploadDir = __DIR__ . "/uploads/"; // Directory to save uploaded files
    $uploadFilePath = $uploadDir . $newFileName;

    // Create the upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Move the file to the upload directory
    if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
        // Save the file path to the database
        $filePathForDb = "uploads/" . $newFileName;
        $query = "UPDATE users SET profile_image = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $filePathForDb, $user_id);

        if ($stmt->execute()) {
            header("Location: home.php?success=1"); // Redirect to profile page with success message
        } else {
            echo "Failed to update profile image in the database.";
        }

        $stmt->close();
    } else {
        echo "Failed to move the uploaded file.";
    }
} else {
    echo "No file was uploaded or there was an error during the upload.";
    exit();
}

// Close the database connection
$conn->close();
?>
