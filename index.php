<?php
// Include the database connection
include('conn.php');

// Initialize variables
$email = $password = "";
$emailError = $passwordError = "";
$loginSuccess = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize it
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Email Validation: Check if it's a valid email format
    if (empty($email)) {
        $emailError = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format.";
    }

    // Password Validation: Ensure it's not empty
    if (empty($password)) {
        $passwordError = "Password is required.";
    }

    // If no errors, proceed with database check
    if (empty($emailError) && empty($passwordError)) {
        // Prepare the SQL query to check if the email exists
        $sql = "SELECT * FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind the parameters
            $stmt->bind_param("s", $email);

            // Execute the query
            $stmt->execute();

            // Store the result
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                // If the email exists, verify the password
                $user = $result->fetch_assoc();
                if (sha1($password) === $user['password']) {
                    // Successful login
                    $loginSuccess = true;
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];

                    // Check the "check" field
                    if ($user['check'] === 'New') {
                        // Redirect to new_user.php if "check" is "New"
                        header("Location: question.php");
                    } else {
                        // Redirect to home.php otherwise
                        header("Location: dash.php");
                    }
                    exit();
                } else {
                    $passwordError = "Incorrect password.";
                }
            } else {
                $emailError = "No account found with that email.";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <div class="fdiv">
        <form method="POST" action="">
            <h2 class="head">LOGIN</h2>
            <h4 class="itali"><i>Don't have an account? <a href="signup.php">Create Account</a></i></h4><br>
            <i style="color:#d61d1d">
                <?php 
                echo $emailError ? htmlspecialchars($emailError) . '<br>' : '';
                echo $passwordError ? htmlspecialchars($passwordError) . '<br>' : '';
                ?>
            </i>
            <br><br>
            <input type="email" name="email" class="inputst" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
            <br><br>
            <input type="password" name="password" class="inputst" placeholder="Password" required>
            <br><br>
            <h4 class="itali1"><i>Forgot your password? <a href="reset.php">Forget now</a></i></h4>
            <button type="submit" class="btn">Login</button>
            <button type="reset" class="btn btn-clear">Clear</button>
        </form>
    </div>
</body>
</html>
