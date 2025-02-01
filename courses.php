<?php
// Connect to the database
$conn = new mysqli('localhost', 'mac', 'pass', 'Edupath_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the university ID from the URL
if (isset($_GET['university_id'])) {
    $university_id = intval($_GET['university_id']);

    // Fetch university details
    $university_query = "SELECT name FROM universities WHERE id = ?";
    $stmt = $conn->prepare($university_query);
    $stmt->bind_param("i", $university_id);
    $stmt->execute();
    $university_result = $stmt->get_result();
    $university = $university_result->fetch_assoc();

    // Fetch courses for this university
    $courses_query = "SELECT sn, program_name, admission_requirements, duration, capacity, tuition_fees 
                      FROM courses WHERE university_id = ? ORDER BY sn ASC";
    $stmt = $conn->prepare($courses_query);
    $stmt->bind_param("i", $university_id);
    $stmt->execute();
    $courses_result = $stmt->get_result();
} else {
    die("Invalid university ID.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - <?php echo htmlspecialchars($university['name']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Courses Offered by <?php echo htmlspecialchars($university['name']); ?></h1>

    <table class="courses-table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Program Name</th>
                <th>Admission Requirements</th>
                <th>Program Duration</th>
                <th>Admission Capacity</th>
                <th>Tuition Fees</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($courses_result && $courses_result->num_rows > 0) {
                while ($course = $courses_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($course['sn']) . "</td>";
                    echo "<td>" . htmlspecialchars($course['program_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($course['admission_requirements']) . "</td>";
                    echo "<td>" . htmlspecialchars($course['duration']) . " years</td>";
                    echo "<td>" . htmlspecialchars($course['capacity']) . "</td>";
                    echo "<td>" . htmlspecialchars($course['tuition_fees']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No courses found for this university.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="universities.php">Back to Universities</a>
</body>
</html>
