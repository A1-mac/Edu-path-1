<?php
// Include the database connection
include('conn.php');

// Initialize variables
$question = $answer = "";
$questionError = $answerError = "";
$successMessage = "";

// Start session to get user ID
session_start();

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize it
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    // Validate question
    if (empty($question)) {
        $questionError = "Please select a security question.";
    }

    // Validate answer
    if (empty($answer)) {
        $answerError = "Please provide an answer to the security question.";
    }

    // If no errors, insert the question and hashed answer into the database
    if (empty($questionError) && empty($answerError)) {
        $hashedAnswer = password_hash($answer, PASSWORD_DEFAULT); // Hash the answer

        // Check if the user already has a security question
        $sqlCheck = "SELECT * FROM security_questions WHERE user_id = ?";
        if ($stmtCheck = $conn->prepare($sqlCheck)) {
            $stmtCheck->bind_param("i", $user_id);
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();

            if ($result->num_rows > 0) {
                // Update existing question and answer
                $sqlUpdate = "UPDATE security_questions SET question = ?, answer_hash = ? WHERE user_id = ?";
                if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                    $stmtUpdate->bind_param("ssi", $question, $hashedAnswer, $user_id);
                    if ($stmtUpdate->execute()) {
                        $successMessage = "Security question updated successfully.";
                        header("Location: new_user.php"); // Redirect to new_user.php
                        exit();
                    } else {
                        echo "Error: " . $stmtUpdate->error;
                    }
                    $stmtUpdate->close();
                }
            } else {
                // Insert a new question and answer
                $sqlInsert = "INSERT INTO security_questions (user_id, question, answer_hash) VALUES (?, ?, ?)";
                if ($stmtInsert = $conn->prepare($sqlInsert)) {
                    $stmtInsert->bind_param("iss", $user_id, $question, $hashedAnswer);
                    if ($stmtInsert->execute()) {
                        $successMessage = "Security question set successfully.";
                        header("Location: new_user1.php"); // Redirect to new_user.php
                        exit();
                    } else {
                        echo "Error: " . $stmtInsert->error;
                    }
                    $stmtInsert->close();
                }
            }
            $stmtCheck->close();
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
    <title>Set Security Question</title>
</head>
<body>
    <div class="fdiv">
        <form method="POST" action="">
            <h2 class="head">Set Security Question</h2>
            <i style="color:#d61d1d">
                <?php
                echo $questionError ? htmlspecialchars($questionError) . '<br>' : '';
                echo $answerError ? htmlspecialchars($answerError) . '<br>' : '';
                echo $successMessage ? htmlspecialchars($successMessage) . '<br>' : '';
                ?>
            </i>
            <br><br>

            <!-- Security Question Dropdown -->
            <label for="question">Select a security question:</label><br>
            <select name="question" id="question" class="inputst" required>
                <option value="">-- Select a question --</option>
                <option value="What is your mother's maiden name?" <?php echo $question == "What is your mother's maiden name?" ? "selected" : ""; ?>>What is your mother's maiden name?</option>
                <option value="What is the name of your first pet?" <?php echo $question == "What is the name of your first pet?" ? "selected" : ""; ?>>What is the name of your first pet?</option>
                <option value="What is your favorite book?" <?php echo $question == "What is your favorite book?" ? "selected" : ""; ?>>What is your favorite book?</option>
                <option value="Where did your study for your diploma?" <?php echo $question == "Where did your study for your diploma?" ? "selected" : ""; ?>>Where did your study for your diploma?</option>
                <option value="Where did your study for nursery?" <?php echo $question == "Where did your study for nursery?" ? "selected" : ""; ?>>Where did your study for your nursery?</option>
            </select>
            <br><br>

            <!-- Answer Field -->
            <input type="text" name="answer" class="inputst" placeholder="Your Answer" value="<?php echo htmlspecialchars($answer); ?>" required>
            <br><br>

            <button type="submit" class="btn">Save</button>
            <button type="reset" class="btn btn-clear">Clear</button>
        </form>
    </div>
</body>
</html>
