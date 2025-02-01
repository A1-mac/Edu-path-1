<?php
// Include the database connection
include('conn.php');

// Initialize variables
$email = $answer = "";
$emailError = $answerError = $successMessage = $securityQuestion = "";
$user_id = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        // Get and validate email
        $email = trim($_POST['email']);
        if (empty($email)) {
            $emailError = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Invalid email format.";
        } else {
            // Check if email exists in users table
            $sql = "SELECT id FROM users WHERE email = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $user = $result->fetch_assoc();
                    $user_id = $user['id']; // Get the user_id

                    // Check if security question exists for the user
                    $sqlQuestion = "SELECT question FROM security_questions WHERE user_id = ?";
                    if ($stmtQuestion = $conn->prepare($sqlQuestion)) {
                        $stmtQuestion->bind_param("i", $user_id);
                        $stmtQuestion->execute();
                        $resultQuestion = $stmtQuestion->get_result();

                        if ($resultQuestion->num_rows == 1) {
                            $questionRow = $resultQuestion->fetch_assoc();
                            $securityQuestion = $questionRow['question'];
                        } else {
                            $emailError = "No security question found for this account.";
                        }

                        $stmtQuestion->close();
                    }
                } else {
                    $emailError = "No account found with this email.";
                }

                $stmt->close();
            }
        }
    } elseif (isset($_POST['answer'])) {
        // Handle security question answer submission
        $answer = trim($_POST['answer']);
        $user_id = $_POST['user_id']; // Retrieve the user_id from a hidden input field

        if (empty($answer)) {
            $answerError = "Answer is required.";
        } else {
            // Validate the answer
            $sqlVerify = "SELECT answer_hash FROM security_questions WHERE user_id = ?";
            if ($stmtVerify = $conn->prepare($sqlVerify)) {
                $stmtVerify->bind_param("i", $user_id);
                $stmtVerify->execute();
                $resultVerify = $stmtVerify->get_result();

                if ($resultVerify->num_rows == 1) {
                    $verifyRow = $resultVerify->fetch_assoc();
                    if (password_verify($answer, $verifyRow['answer_hash'])) {
                        // Redirect to update password page
                        session_start();
                        $_SESSION['user_id'] = $user_id;
                        header("Location: update_password.php");
                        exit();
                    } else {
                        $answerError = "Incorrect answer.";
                    }
                } else {
                    $answerError = "Security question not found.";
                }

                $stmtVerify->close();
            }
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
    <title>Reset Password</title>
</head>
<body>
    <div class="fdiv">
        <form method="POST" action="">
            <h2 class="head">Reset Password</h2>
            <i style="color:#d61d1d">
                <?php
                echo $emailError ? htmlspecialchars($emailError) . '<br>' : '';
                echo $answerError ? htmlspecialchars($answerError) . '<br>' : '';
                ?>
            </i>
            <br><br>
            <!-- Email Input -->
            <?php if (empty($securityQuestion)) { ?>
                <input type="email" name="email" class="inputst" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
                <br><br>
                <button type="submit" class="btn">Next</button>
                <button type="button" class="btn btn-clear" onclick="window.location.href='index.php'">Back</button>

            <?php } else { ?>
                <!-- Display Security Question -->
                <label for="question"><?php echo htmlspecialchars($securityQuestion); ?></label><br><br>
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <input type="text" name="answer" class="inputst" placeholder="Your Answer" value="<?php echo htmlspecialchars($answer); ?>" autocomplete="off" required>
                <br><br>
                <button type="submit" class="btn">Submit Answer</button>
            <?php } ?>
        </form>
    </div>
</body>
</html>
