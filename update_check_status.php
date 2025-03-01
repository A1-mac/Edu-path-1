<?php
// Include the database connection
include('conn.php');

// Get the data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Check if the user_id is provided
if (isset($data['user_id'])) {
    $user_id = $data['user_id'];

    // Update the "check" field in the users table to 'Old'
    $sql = "UPDATE users SET `check` = 'Old' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        echo "Success: Data submitted and check status updated.";
    } else {
        echo "Error: Unable to update check status.";
    }
} else {
    echo "Error: No user ID provided.";
}
?>
