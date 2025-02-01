<?php
// Include the database connection
include('conn.php');

// Start session
session_start();

// Redirect to login if session is not set
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check the "check" field in the database
$sql = "SELECT `check` FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Redirect to home.php if "check" is not "New"
if ($user['check'] !== 'New') {
    header("Location: home.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $education_level = $_POST['education_level'];
    $division = (int)$_POST['division'];
    $combination1 = trim($_POST['combination1']);
    $grade1 = trim($_POST['grade1']);
    $combination2 = trim($_POST['combination2']);
    $grade2 = trim($_POST['grade2']);
    $combination3 = trim($_POST['combination3']);
    $grade3 = trim($_POST['grade3']);

    // Validate inputs
    $errors = [];
    if (!in_array($education_level, ['CSEE', 'ACSEE'])) {
        $errors[] = "Invalid education level.";
    }
    if ($division < 1 || $division > 4) {
        $errors[] = "Division must be between 1 and 4.";
    }
    if (empty($combination1) || empty($grade1) || empty($combination2) || empty($grade2) || empty($combination3) || empty($grade3)) {
        $errors[] = "All combinations and grades are required.";
    }

    // Validate grades
    $valid_grades = ['A', 'A+', 'B', 'B+', 'C', 'D', 'E', 'F'];
    if (!in_array(strtoupper($grade1), $valid_grades) || !in_array(strtoupper($grade2), $valid_grades) || !in_array(strtoupper($grade3), $valid_grades)) {
        $errors[] = "Invalid grade(s). Grades must be A, A+, B, B+, C, D, E, or F.";
    }



    // If no errors, insert into the database
    if (empty($errors)) {
        $sql = "INSERT INTO user_education (user_id, education_level, division, preferred_combination_1, grade1, preferred_combination_2, grade2, preferred_combination_3, grade3)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isissssss", $user_id, $education_level, $division, $combination1, $grade1, $combination2, $grade2, $combination3, $grade3);
        if ($stmt->execute()) {
            // Update the "check" field in the users table to "Old"
            $update_sql = "UPDATE users SET `check` = 'Old' WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user_id);
            $update_stmt->execute();

            // Redirect to home.php after successful submission
            header("Location: dash.php");
            exit();
        } else {
            $errors[] = "Error saving data: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>New User Education Form</title>
</head>
<body>
    <div class="fdiv">
        <form method="POST" action="">
            <h2 class="head">Education Information</h2>
            <?php if (!empty($errors)): ?>
            <div style="color: #d61d1d; margin-bottom: 15px;">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
            <label for="education_level">Education Level:</label>
            <select name="education_level" class="level" id="education_level" required>
                <option value="">Select For</option>
                <option value="CSEE">Form 4</option>
                <option value="ACSEE">Form 6</option>
            </select>
            <br><br>

            <label for="division">Division (1-4):</label>
            <input type="number" name="division" id="division" class="inputst1" min="1" max="4" required>
            <br><br>
            <h3>Prefered Combination</h3>

            <label for="combination1">Subject 1 and Grade:</label>
            <input type="text" placeholder="Subject"  name="combination1" class="inputst2" id="combination1" required>
            <input type="text" placeholder="Grade (eg; A+, B, etc)"  name="grade1" class="inputst2" id="grade1" maxlength="2" required>
            <br><br>

            <label for="combination2">Subject 2 and Grade:</label>
            <input type="text" placeholder="Subject" name="combination2" class="inputst2" id="combination2" required>
            <input type="text" name="grade2" placeholder="Grade (eg; A+, B, etc)"   class="inputst2" id="grade2" maxlength="2" required>
            <br><br>

            <label for="combination3">Subject 3 and Grade:</label>
            <input type="text" name="combination3" placeholder="Subject"  class="inputst2" id="combination3" required>
            <input type="text" name="grade3" placeholder="Grade (eg; A+, B, etc)"   class="inputst2" id="grade3" maxlength="2" required>
            <br><br>

            <button type="submit" class="btn">Submit</button>
            <button type="reset" class="btn btn-clear">Clear</button>
        </form>
    </div>
</body>
</html>
