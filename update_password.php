<?php
// Include the database connection
include('conn.php');

// Start session
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: security_check.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user_id from session
$passwordError = $successMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = trim($_POST['password']);

    if (empty($password)) {
        $passwordError = "Password is required.";
    } elseif (strlen($password) < 8) {
        $passwordError = "Password must be at least 8 characters.";
    }elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
        $passwordError = "Password must be alphanumeric.";
    } else {
        // Check if the new password matches the old password
        $sql = "SELECT password FROM users WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($oldPasswordHash);
            $stmt->fetch();
            $stmt->close();

            if (sha1($password) === $oldPasswordHash) {
                $passwordError = "New password cannot be the same as the old password.";
            } else {
                // Hash the new password
                $hashedPassword = sha1($password);

                // Update password in the database
                $sql = "UPDATE users SET password = ? WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("si", $hashedPassword, $user_id);
                    if ($stmt->execute()) {
                        // Success
                        session_destroy(); // Clear session
                        header("Location: index.php");
                        exit();
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                }
            }
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Update Password</title>
</head>
<body>
    <div class="fdiv">
    <form method="POST" action="">
    <h2 class="head">Update Password</h2>
    <i style="color:#d61d1d">
        <?php echo $passwordError ? htmlspecialchars($passwordError) . '<br>' : ''; ?>
    </i>
    <br><br>
    <!-- New Password Field -->
    <input type="password" name="password" id="password" class="inputst" placeholder="New Password" required>
    <br><br>
    <!-- Re-enter Password Field -->
    <input type="password" name="confirm_password" id="confirm_password" class="inputst" placeholder="Re-enter Password" required>
    <br><br>
    <!-- Error Message for Mismatched Passwords -->
    <i style="color:#d61d1d" id="passwordMismatchError"></i>
    <br><br>
    <button type="submit" class="btn" id="submitButton">Update Password</button>
    </form>
    </div>
    <script>
    // JavaScript for validating password match
    const form = document.querySelector('form');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm_password');
    const errorElement = document.getElementById('passwordMismatchError');
    const submitButton = document.getElementById('submitButton');

    form.addEventListener('submit', function (event) {
        // Clear any previous error
        errorElement.textContent = '';

        // Check if passwords match
        if (passwordField.value !== confirmPasswordField.value) {
            event.preventDefault(); // Prevent form submission
            errorElement.textContent = 'Passwords do not match!';
        }
    });
</script>
</body>
</html>
