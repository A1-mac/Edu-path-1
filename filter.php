<?php

// Database connection parameters
$servername = "localhost";
$dbusername = "mac";        // Replace with your MySQL username
$dbpassword = "pass";       // Replace with your MySQL password
$dbname = "Edupath_db";     // The database name

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Get the user_id from the session
$user_id = 20;// Replace 1 with actual session data

// Fetch user's education level
$sql_education = "SELECT exam_type FROM student_results WHERE user_id = $user_id";
$result_education = $conn->query($sql_education);
$user_education_level = "";

if ($result_education->num_rows > 0) {
    $row = $result_education->fetch_assoc();
    $user_education_level = strtolower(trim($row['exam_type'])); // Convert to lowercase for comparison
}

// Fetch student's subject results
$sql_results = "
    SELECT ss.subject_name, ss.score
    FROM student_subjects ss
    INNER JOIN student_results s ON ss.student_id = s.id
    WHERE s.user_id = $user_id
";
$result_set = $conn->query($sql_results);

$subject_names = [
    "GEO" => "Geography",
    "CHEM" => "Chemistry",
    "ENGL" => "English",
    "ENG SC" => "English Science",
    "B/MATH" => "Mathematics",
    "PHY" => "Physics",
    "BIO" => "Biology",
    "HIST" => "History",
    "KISW" => "Kiswahili",
    "COMM" => "Commerce",
    "BOOK" => "Bookkeeping",
    "CIV" => "Civics",
    "COMP" => "Computer Science",
    "LIT ENG" => "Literature in English",
    "FRENCH" => "French",
    "ARABIC" => "Arabic",
    "BAM" => "Basic Applied Mathematics",
    "G/STUDIES" => "General Studies",
    "HISTORY" => "History",
    "GEOGR" => "Geography",
    "COMP STUD" => "Computer Studies",
];

$pass_count = 0;
$passed_subjects = [];
$passing_grades = ['A', 'B', 'C'];

if ($result_set->num_rows > 0) {
    while ($row = $result_set->fetch_assoc()) {
        // Check if the grade is passing
        if (in_array($row['score'], $passing_grades)) {
            $pass_count++;

            // Get the full subject name from the array
            $subject_code = trim($row['subject_name']); // Convert to uppercase for matching
            if (array_key_exists($subject_code, $subject_names)) {
                $full_subject_name = $subject_names[$subject_code];
                $passed_subjects[] = $full_subject_name; // Add the full subject name to the list
            } else {
                // In case the subject code is not found in the mapping
                $passed_subjects[] = "Unknown Subject ($subject_code)";
            }
        }
    }
}



// Pagination setup
$limit = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $limit;

// Fetch ALL courses that have data in total_pass, cetificate, and specialSubject
// For total_pass, we check that it is not null or zero, and for cetificate and specialSubject, we ensure they are not empty.
$sql_courses = "SELECT c.id, c.program_name, c.university_name, c.total_pass, c.cetificate, c.specialSubject
                FROM courses c
                WHERE c.total_pass IS NOT NULL 
                  AND c.total_pass > 0 
                  AND c.cetificate <> '' 
                  AND c.specialSubject <> ''";
$result_courses = $conn->query($sql_courses);

$eligible_courses = [];

if ($result_courses->num_rows > 0) {
    while ($row = $result_courses->fetch_assoc()) {
        $required_passes = intval($row['total_pass']);
        $required_certificate = strtolower(trim($row['cetificate']));
        
        // Convert specialSubject string to an array, removing square brackets if present
        $special_subject_raw = trim(str_replace(['[', ']'], '',($row['specialSubject'])));
        $subject_requirements = !empty($special_subject_raw) ? array_map('trim', explode(',', $special_subject_raw)) : [];
        
        $eligible = true;

        // Check total passes requirement
        if ($pass_count < $required_passes) {
            $eligible = false;
        }

        // Check education level against required certificate
        if (!empty($required_certificate) && $user_education_level !== $required_certificate) {
            $eligible = false;
        }

        // If there are required subjects, ensure the student passed them.
        // If specialSubject is empty, this check is skipped.
        if (!empty($subject_requirements)) {// Assume eligible by default
            foreach ($subject_requirements as $subject) {
                if (!in_array($subject, $passed_subjects)) {
                    $eligible = false; // If even one subject is missing, mark as ineligible
                    break;
                }
            }
        }

        if ($eligible) {
            $eligible_courses[] = $row;
        }
    }
}

$total_courses = count($eligible_courses);

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .price-range input {
            width: 48%;
            display: inline-block;
        }
        button {
            background-color: #28a745; 
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        h2{
            margin-bottom: 5px;
        }
        .fade{
            color: #5f5f5fc6;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Filter Your Course</h2>
        <i>The courses that you are eligible at are :</i> <?php echo $total_courses;  ?><br>
        <i class="fade">You can filter by,</i>
        <br><br>
        <form action="search_results.php" method="POST">
            <!-- Location (Now as Text Input) -->
            <div class="form-group">
                <label for="location">Location</label>
                <input list="locations" id="location" name="location" placeholder="Type or select location..." oninput="fetchLocations(this.value)">
                <datalist id="locations"></datalist>
            </div>

            <!-- Price Range (Now in Tanzanian Shillings - TZS) -->
            <div class="form-group price-range">
                <label>Tuition Fee Range (TZS)</label><br>
                <input type="text" id="min-price" name="min_price" min="0" placeholder="Min price (TZS)" oninput="formatNumber(this)">
                <input type="text" id="max-price" name="max_price" min="0" required placeholder="Max price (TZS)" oninput="formatNumber(this)">
                <p id="error-message" style="color: red; display: none;">Min price cannot be greater than Max price!</p>
            </div>

            <!-- Submit Button -->
            <button type="submit">Search Courses</button>
        </form>
    </div>
    <script>
    function formatNumber(input) {
        // Remove all non-digit characters except numbers
        let value = input.value.replace(/,/g, '').replace(/\D/g, '');
        
        // Format number with commas
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        // Validate Min/Max values
        validatePriceRange();
    }

    function validatePriceRange() {
    let minPrice = document.getElementById("min-price").value.replace(/,/g, '');
    let maxPrice = document.getElementById("max-price").value.replace(/,/g, '');
    let errorMessage = document.getElementById("error-message");
    let submitButton = document.querySelector("button[type='submit']");

        if (minPrice !== "" && maxPrice !== "" && parseInt(minPrice) > parseInt(maxPrice)) {
            errorMessage.style.display = "block"; // Show error message
            submitButton.disabled = true; // Disable the button
            submitButton.style.backgroundColor = "#ccc"; // Change button color to grey
            submitButton.style.cursor = "not-allowed"; // Change cursor
        } else {
            errorMessage.style.display = "none"; // Hide error message
            submitButton.disabled = false; // Enable the button
            submitButton.style.backgroundColor = "#28a745"; // Restore button color
            submitButton.style.cursor = "pointer"; // Restore cursor
        }
    }
// live search function
function fetchLocations(query) {
    if (query.length < 2) return; // Start searching after 2 characters

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "search_location.php?q=" + encodeURIComponent(query), true);
    xhr.onreadystatechange = function() {
    if (xhr.readyState == 4) {
        console.log("Response:", xhr.responseText); // Debugging
        if (xhr.status == 200) {
            document.getElementById("locations").innerHTML = xhr.responseText;
        } else {
            console.log("Error fetching locations:", xhr.statusText);
        }
    }
};
    xhr.send();
}
    </script>
</body>
</html>