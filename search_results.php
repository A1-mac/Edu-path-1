<?php
include('conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get filter values
$location = isset($_POST['location']) ? trim($_POST['location']) : "";
$min_price = isset($_POST['min_price']) ? str_replace(",", "", $_POST['min_price']) : 0;
$max_price = isset($_POST['max_price']) ? str_replace(",", "", $_POST['max_price']) : 0;

// Step 1: Fetch courses that match tuition fee constraints
$sql_price_filter = "
    SELECT program_name, university_name, tuition_fees,
        CAST(
            REPLACE(
                TRIM(BOTH ' /= ' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(tuition_fees, 'Local Fee: TSH. ', -1), '/=', 1)),
                ',', ''
            ) AS UNSIGNED
        ) AS extracted_fee
    FROM courses
    HAVING extracted_fee BETWEEN $min_price AND $max_price;
";

$result_price = $conn->query($sql_price_filter);

$valid_universities = [];
if ($result_price->num_rows > 0) {
    while ($row = $result_price->fetch_assoc()) {
        $valid_universities[$row['university_name']] = true;
    }
}

// Step 2: Fetch eligible courses (Compare tuition fee & location)
$sql_courses = "
    SELECT c.id, c.program_name, c.university_name, c.total_pass, c.cetificate, c.specialSubject, u.head_office,
        CAST(
            REPLACE(
                TRIM(BOTH ' /= ' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(c.tuition_fees, 'Local Fee: TSH. ', -1), '/=', 1)),
                ',', ''
            ) AS UNSIGNED
        ) AS extracted_fee
    FROM courses c
    INNER JOIN universities u ON c.university_name = u.name
    WHERE c.total_pass IS NOT NULL 
      AND c.total_pass > 0 
      AND c.cetificate <> '' 
      AND c.specialSubject <> ''
";

if (!empty($location)) {
    $sql_courses .= " AND u.head_office LIKE '%$location%'";
}

$result_courses = $conn->query($sql_courses);

$eligible_courses = [];
if ($result_courses->num_rows > 0) {
    while ($row = $result_courses->fetch_assoc()) {
        if (isset($valid_universities[$row['university_name']])) {
            $eligible_courses[] = $row;
        }
    }
}

// Step 3: Save search history
$stmt = $conn->prepare("INSERT INTO search_history (user_id, location, min_price, max_price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issi", $user_id, $location, $min_price, $max_price);
$stmt->execute();
$stmt->close();

$total_courses = count($eligible_courses);


// Step 4: Redirect to dash.php if results found
if ($total_courses > 0) {
    $_SESSION['eligible_courses'] = $eligible_courses;
    // Step 5: Update 'check' field to 'Old' after filtering is complete
    $update_check_sql = "UPDATE users SET `check` = 'Old' WHERE id = ?";
    $stmt = $conn->prepare($update_check_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: dash.php");
    exit();
} else {
    echo "<script>alert('No courses found. Try different filters.'); window.history.back();</script>";
}
?>
