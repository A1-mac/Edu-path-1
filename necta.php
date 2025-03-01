<?php

// Include the database connection
include('conn.php');

// Start session
session_start();

// Redirect to login if session is not set
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check the "check" field in the database (for education information)
$sql = "SELECT `check` FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Redirect to home.php if "check" is not "New" (i.e. user already submitted education info)
if ($user['check'] !== 'New') {
    header("Location: home.php");
    exit();
}


$result = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $indexNumber = trim($_POST['index_number']);
    $examTypeInput = trim($_POST['exam_type']);

    if (preg_match('/^([A-Z0-9]+)\/(\d+)\/(\d{4})$/i', $indexNumber, $matches)) {
        $schoolNumber = escapeshellarg($matches[1]);
        $studentNumber = escapeshellarg($matches[2]);
        $year = escapeshellarg($matches[3]);
        $examType = escapeshellarg(strtoupper($examTypeInput));

        $command = "python3 get_results.py $year $examType $schoolNumber $studentNumber 2>/dev/null";
        $output = shell_exec($command);

        if ($output === null) {
            $result = "<p style='color:red;'>Error: No output from Python script.</p>";
        } else {
            $output = trim($output); // Remove extra spaces
            $jsonData = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $result = "<p style='color:red;'>Error: Invalid JSON format.</p>";
            } elseif (isset($jsonData["error"])) {
                $result = "<p style='color:red;'><strong>Error:</strong> " . htmlspecialchars($jsonData["error"]) . "</p>";
            } else {
                $result  = "<h3>Student Results</h3>";
                $result .= "<p><strong>Examination Number:</strong> " . htmlspecialchars($jsonData["examination_number"]) . "</p>";
                $result .= "<p><strong>Year:</strong> " . htmlspecialchars($jsonData["year_of_exam"]) . "</p>";
                $result .= "<p><strong>Exam Type:</strong> " . htmlspecialchars($jsonData["exam_type"]) . "</p>";
                // Map gender short form to long form
                $gender = isset($jsonData["gender"]) ? strtoupper($jsonData["gender"]) : "";
                $gender_long_form = ($gender === "M") ? "Male" : (($gender === "F") ? "Female" : "Unknown");

                // Display gender with long form
                $result .= "<p><strong>Gender:</strong> " . htmlspecialchars($gender_long_form) . "</p>";

                $result .= "<p><strong>Division:</strong> " . htmlspecialchars($jsonData["division"]) . "</p>";
                $result .= "<p><strong>Points:</strong> " . htmlspecialchars($jsonData["points"]) . "</p>";

                $result .= "<h4>Subjects & Scores</h4>";
                if (isset($jsonData["subjects"]) && is_array($jsonData["subjects"])) {
                    // Subject name mappings (short form to long form)
                    $subject_names = [
                        "GEO" => "Geography",
                        "CHEM" => "Chemistry",
                        "ENGL" => "English" ,
                        "ENG SC" => "English Science",
                        "B/MATH" => "Basic Mathematics",
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
                    ];
                
                    // Table with inline CSS for styling
                    $result .= "<table style='width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;'>";
                    $result .= "<tr style='background-color: #f2f2f2; color: #333;'>
                                    <th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Subject</th>
                                    <th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Score</th>
                                </tr>";
                
                    foreach ($jsonData["subjects"] as $subject => $score) {
                        $long_subject = isset($subject_names[$subject]) ? $subject_names[$subject] : $subject;
                        $result .= "<tr style='color: #555;'>
                                        <td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($long_subject) . "</td>
                                        <td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($score) . "</td>
                                    </tr>";
                    }
                
                    $result .= "</table>";

                    $result .= '<button id="submitData" style="position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">Submit Data</button>';
                    $result .= "
                    <script>
                    document.getElementById('submitData').addEventListener('click', function() {
                        var jsonData = " . json_encode($jsonData) . "; // Convert PHP array to JavaScript object

                        fetch('submit_data.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(jsonData)
                        })
                        .then(response => response.text())
                        .then(data => {
                            alert(data); // Show success or error message
                            if (data.includes('Success')) { // Check if the response is successful
                                // Update \check\ field to Old
                                fetch('update_check_status.php', {
                                    method: 'POST',
                                    body: JSON.stringify({ user_id: " . $user_id . " })
                                }).then(response => response.text())
                                  .then(response => {
                                      window.location.href = 'dash.php'; // Redirect to dashboard
                                  });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while submitting data.');
                        });
                    });
                    </script>";

                } else {
                    $result .= "<p style='color: #555;'>No subject scores available.</p>";
                }
                

            }
        }
    } else {
        $result = "<p style='color:red;'><strong>Error:</strong> Invalid Index Number format.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NECTA Student Results</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            padding: 20px; 
            background-color: #e7ebef;
            margin: 60px;
        }
        form { 
            margin-top:20px;
            margin-bottom: 20px; 
            background: #fff; 
            padding: 20px; 
            border-radius: 5px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        input, select { 
            padding: 10px; 
            font-size: 16px; 
            width: 300px; 
            margin-bottom: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .appearance-settings {
            border: 2px solid #4a90e2;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }

        .appearance-settings:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .appearance-settings h3 {
            color: #4a90e2;
            font-size: 20px;
            margin-bottom: 15px;
        }
        button { 
            padding: 10px; 
            font-size: 16px; 
            cursor: pointer; 
            background: #007BFF; 
            color: #fff; 
            border: none; 
            border-radius: 4px;
        }
        button:hover { background: #0056b3; }
        #result { 
            margin-top: 20px; 
            background: #f4f4f4; 
            padding: 15px; 
            border-radius: 5px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h3, h4 { color: #333; }
        p{ color: #555; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Student Education Information: NECTA Results</h2>
    <div class="card appearance-settings">
    <form method="POST">
        <label for="index_number">Index Number (e.g., P0176/1234/2024):</label><br>
        <input type="text" id="index_number" name="index_number" placeholder="Enter Index Number" required><br>

        <label for="exam_type">Exam Type (e.g., CSEE, ACSEE):</label><br>
        <input type="text" id="exam_type" name="exam_type" placeholder="Enter Exam Type" required><br>

        <button type="submit">Search</button>
    </form>
    </div>

    <div id="result" class="card appearance-settings">
        <?php echo $result; ?>
    </div>
</body>
</html>
