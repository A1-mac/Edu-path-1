<?php
// Include the database connection
include('conn.php');

$fullname = $email = $password = $recheck_password = $phone = "";
$emailError = $passwordError = $fullnameError = $existingUserError = $phoneError = ""; // Initialize phoneError
$signupSuccess = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $recheck_password = trim($_POST['recheck_password']);
    $phone = trim($_POST['phone']); // Get the phone number

    // Full Name Validation: Only letters and spaces
    if (empty($fullname)) {
        $fullnameError = "Full Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $fullname)) {
        $fullnameError = "Full Name must contain only letters and spaces.";
    }

    // Email Validation: Check if it's a valid email format
    if (empty($email)) {
        $emailError = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format.";
    }

    // Phone Validation: Only numbers allowed and minimum 10 digits
    if (empty($phone)) {
        $phoneError = "Phone number is required.";
    } elseif (!preg_match("/^[0-9]{10,}$/", $phone)) {
        $phoneError = "Phone number must contain only numbers and be at least 10 digits long.";
    }

    // Password Validation: Check for minimum length and alphanumeric characters
    if (empty($password)) {
        $passwordError = "Password is required.";
    } elseif (strlen($password) < 8) {
        $passwordError = "Password must be at least 8 characters.";
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
        $passwordError = "Password must be alphanumeric.";
    }

    // Recheck Password Validation: Must match the original password
    if (empty($recheck_password)) {
        $passwordError = "Please recheck your password.";
    } elseif ($password != $recheck_password) {
        $passwordError = "Passwords do not match.";
    }

    // If there are no errors, proceed to check for existing user
    if (empty($fullnameError) && empty($emailError) && empty($phoneError) && empty($passwordError)) {
        // Check if user with the same email or name already exists
        $sql = "SELECT * FROM users WHERE email = ? OR name = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $email, $fullname);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $existingUserError = "A user with this email or name already exists.";
            } else {
                // No duplicate user found, proceed to insert data
                $hashedPassword = sha1($password); // Use sha1 for password encryption

                $sqlInsert = "INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)";
                if ($stmtInsert = $conn->prepare($sqlInsert)) {
                    $stmtInsert->bind_param("ssss", $fullname, $email, $hashedPassword, $phone);
                    if ($stmtInsert->execute()) {
                        $signupSuccess = true;
                        // Redirect to index.php on successful sign-up
                        header("Location: index.php");
                        exit; // Always use exit after header redirection
                    } else {
                        echo "Error: " . $stmtInsert->error;
                    }
                    $stmtInsert->close();
                }
            }
            $stmt->close();
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
    <title>Sign Up</title>
</head>
<body>
    <div class="fdiv">
    <form id="signupForm" method="POST" action="">
            <H2 class="head">Sign Up</H2>
            <h4 class="itali"><i>Already have an account? <a href="index.php">Login here</a></i></h4>
            <br>
            <i style="color:#d61d1d">
    <?php 
    echo $fullnameError ? htmlspecialchars($fullnameError) . '<br>' : '';
    echo $emailError ? htmlspecialchars($emailError) . '<br>' : '';
    echo $phoneError ? htmlspecialchars($phoneError) . '<br>' : ''; // Added phone error display
    echo $passwordError ? htmlspecialchars($passwordError) . '<br>' : '';
    echo $existingUserError ? htmlspecialchars($existingUserError) : '';
    ?>
</i>

            <br><br>

            <input type="text" name="fullname" class="inputst" placeholder="Full Name" value="<?php echo htmlspecialchars($fullname); ?>" required>
            <br><br>

            <input type="email" name="email" class="inputst" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
            <br><br>
            
            <input type="text" name="phone" class="inputst" placeholder="Phone (Numbers Only)" value="<?php echo htmlspecialchars($phone); ?>" required pattern="[0-9]+" title="Phone number must contain only numbers.">
            <br><br>

            <input type="password" name="password" class="inputst" placeholder="Password" required>
            <br><br>

            <input type="password" name="recheck_password" class="inputst" placeholder="Recheck Password" required>
            <br>

            <button type="submit" class="btn">Sign Up</button>
            <button type="reset" class="btn btn-clear">Clear</button>
        </form>
    </div>
</body>
</html>
