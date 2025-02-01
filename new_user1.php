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

// Initialize error arrays for both sections
$edu_errors = [];
$results_errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ---------- Process Education Information ----------
    // (These fields are always present in the combined form)
    $education_level = isset($_POST['education_level']) ? $_POST['education_level'] : "";
    $division = isset($_POST['division']) ? (int)$_POST['division'] : 0;
    $combination1 = isset($_POST['combination1']) ? trim($_POST['combination1']) : "";
    $grade1 = isset($_POST['grade1']) ? trim($_POST['grade1']) : "";
    $combination2 = isset($_POST['combination2']) ? trim($_POST['combination2']) : "";
    $grade2 = isset($_POST['grade2']) ? trim($_POST['grade2']) : "";
    $combination3 = isset($_POST['combination3']) ? trim($_POST['combination3']) : "";
    $grade3 = isset($_POST['grade3']) ? trim($_POST['grade3']) : "";

    // Validate education info inputs
    if (!in_array($education_level, ['CSEE', 'ACSEE'])) {
        $edu_errors[] = "Invalid education level.";
    }
    if ($division < 1 || $division > 4) {
        $edu_errors[] = "Division must be between 1 and 4.";
    }
    if (empty($combination1) || empty($grade1) ||
        empty($combination2) || empty($grade2) ||
        empty($combination3) || empty($grade3)) {
        $edu_errors[] = "All preferred combinations and grades are required.";
    }
    $valid_grades = ['A', 'A+', 'B', 'B+', 'C', 'D', 'E', 'F'];
    if (!in_array(strtoupper($grade1), $valid_grades) ||
        !in_array(strtoupper($grade2), $valid_grades) ||
        !in_array(strtoupper($grade3), $valid_grades)) {
        $edu_errors[] = "Invalid grade(s) for preferred combinations. Allowed: A, A+, B, B+, C, D, E, F.";
    }

    // ---------- Process Student Results ----------
    // (These fields come from the second section of the combined form)
    $result_education_level = isset($_POST['result_education_level']) ? $_POST['result_education_level'] : "";
    $result_track = isset($_POST['result_track']) ? $_POST['result_track'] : "";
    $result_combination = isset($_POST['result_combination']) ? trim($_POST['result_combination']) : "";

    // Validate education level for student results
    if (!in_array($result_education_level, ['CSEE', 'ACSEE'])) {
        $results_errors[] = "Invalid education level for student results.";
    }
    // For CSEE, track selection is required.
    if ($result_education_level === 'CSEE') {
        $allowed_tracks = ['Arts', 'Science', 'Business'];
        if (!in_array($result_track, $allowed_tracks)) {
            $results_errors[] = "Invalid or missing track for CSEE.";
        }
    }
    // For ACSEE, the combination code must be provided.
    if ($result_education_level === 'ACSEE') {
        if (empty($result_combination)) {
            $results_errors[] = "Combination Code is required for ACSEE.";
        }
    }

    // Process the 8 subject rows (subject1 to subject8 with corresponding grade1 to grade8)
    $subjects = [];
    for ($i = 1; $i <= 8; $i++) {
        $subj = isset($_POST["subject$i"]) ? trim($_POST["subject$i"]) : "";
        $grade = isset($_POST["grade$i"]) ? trim($_POST["grade$i"]) : "";
        if (empty($subj) || empty($grade)) {
            $results_errors[] = "Subject $i and its grade are required.";
        }
        // Validate grade for each subject (using a simpler set for this example)
        $valid_result_grades = ['A', 'B', 'C', 'D', 'E', 'F'];
        if (!in_array(strtoupper($grade), $valid_result_grades)) {
            $results_errors[] = "Invalid grade for subject $i. Allowed: A, B, C, D, E, F.";
        }
        $subjects[] = ['name' => $subj, 'grade' => $grade];
    }

    // If no errors in either section, process database inserts.
    if (empty($edu_errors) && empty($results_errors)) {

        // ----- Insert Education Information -----
        $sqlEdu = "INSERT INTO user_education (user_id, education_level, division, 
                    preferred_combination_1, grade1, preferred_combination_2, grade2, 
                    preferred_combination_3, grade3)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtEdu = $conn->prepare($sqlEdu);
        $stmtEdu->bind_param("isissssss", $user_id, $education_level, $division, 
                             $combination1, $grade1, $combination2, $grade2, $combination3, $grade3);
        if ($stmtEdu->execute()) {
            // Update the "check" field in the users table to "Old"
            $update_sql = "UPDATE users SET `check` = 'Old' WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user_id);
            $update_stmt->execute();
        } else {
            $edu_errors[] = "Error saving education data: " . $conn->error;
        }

        // ----- Insert Student Results (all subjects in one multi-row INSERT) -----
        // Build a multi-value INSERT query.
        $values = [];
        $types = "";
        $params = [];
        // For each subject add a set of placeholders.
        foreach ($subjects as $subject) {
            $values[] = "(?, ?, ?)";
            $types .= "iss";  // user_id (int), subject_name (string), grade (string)
            $params[] = $user_id;
            $params[] = $subject['name'];
            $params[] = $subject['grade'];
        }
        $sqlResults = "INSERT INTO student_results (user_id, subject_name, grade) VALUES " . implode(", ", $values);
        $stmtResults = $conn->prepare($sqlResults);
        if ($stmtResults) {
            // Bind parameters dynamically
            $stmtResults->bind_param($types, ...$params);
            if (!$stmtResults->execute()) {
                $results_errors[] = "Error saving student results: " . $stmtResults->error;
            }
        } else {
            $results_errors[] = "Preparation error for student results: " . $conn->error;
        }

        // If both inserts succeeded, redirect.
        if (empty($edu_errors) && empty($results_errors)) {
            header("Location: dash.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <title>New User Education &amp; Results Form</title>
  <style>
    /* Simple inline styles for demonstration */
    .hidden { display: none; }
    .error { color: #d61d1d; margin-bottom: 15px; }
    .subject-row { margin-bottom: 10px; }
    .track-btns button { margin-right: 10px; }
    .section { border: 1px solid #ccc; padding: 10px; margin-bottom: 15px; }
  </style>
</head>
<body>
  <div class="fdiv">
    <!-- Combined Form for Education Information and Student Results -->
    <form method="POST" action="">
      <h2 class="head">Education Information</h2>
      <?php if (!empty($edu_errors)): ?>
      <div class="error">
        <?php foreach ($edu_errors as $error): ?>
          <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="section">
        <label for="education_level">Education Level:</label>
        <select name="education_level" id="education_level" required>
          <option value="">Select Form</option>
          <option value="CSEE" <?php if(isset($education_level) && $education_level=="CSEE") echo "selected"; ?>>Form 4 (CSEE)</option>
          <option value="ACSEE" <?php if(isset($education_level) && $education_level=="ACSEE") echo "selected"; ?>>Form 6 (ACSEE)</option>
        </select>
        <br><br>

        <label for="division">Division (1-4):</label>
        <input type="number" name="division" id="division" min="1" max="4" required value="<?php echo isset($division)? $division : ''; ?>">
        <br><br>

        <h3>Preferred Combination</h3>
        <label for="combination1">Subject 1 and Grade:</label>
        <input type="text" name="combination1" id="combination1" placeholder="Subject" required value="<?php echo isset($combination1)? htmlspecialchars($combination1) : ''; ?>">
        <input type="text" name="grade1" id="grade1" placeholder="Grade (e.g., A+)" maxlength="2" required value="<?php echo isset($grade1)? htmlspecialchars($grade1) : ''; ?>">
        <br><br>

        <label for="combination2">Subject 2 and Grade:</label>
        <input type="text" name="combination2" id="combination2" placeholder="Subject" required value="<?php echo isset($combination2)? htmlspecialchars($combination2) : ''; ?>">
        <input type="text" name="grade2" id="grade2" placeholder="Grade (e.g., B)" maxlength="2" required value="<?php echo isset($grade2)? htmlspecialchars($grade2) : ''; ?>">
        <br><br>

        <label for="combination3">Subject 3 and Grade:</label>
        <input type="text" name="combination3" id="combination3" placeholder="Subject" required value="<?php echo isset($combination3)? htmlspecialchars($combination3) : ''; ?>">
        <input type="text" name="grade3" id="grade3" placeholder="Grade (e.g., A)" maxlength="2" required value="<?php echo isset($grade3)? htmlspecialchars($grade3) : ''; ?>">
        <br><br>
      </div>

      <hr>

      <h2 class="head">Student Results</h2>
      <?php if (!empty($results_errors)): ?>
      <div class="error">
        <?php foreach ($results_errors as $error): ?>
          <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="section">
        <label for="result_education_level">Education Level:</label>
        <select name="result_education_level" id="result_education_level" required>
          <option value="">Select Education Level</option>
          <option value="CSEE">CSEE (Form 4)</option>
          <option value="ACSEE">ACSEE (Form 6)</option>
        </select>
        <br><br>

        <!-- Div for track selection (only applicable for CSEE) -->
        <div id="trackSelection" class="track-btns hidden">
          <p>Select Track:</p>
          <button type="button" class="track-btn" data-track="Arts">Arts</button>
          <button type="button" class="track-btn" data-track="Science">Science</button>
          <button type="button" class="track-btn" data-track="Business">Business</button>
        </div>
        <br>

        <!-- Hidden field to hold the selected track -->
        <input type="hidden" name="result_track" id="result_track" value="">

        <!-- Div for combination code (only for ACSEE) -->
        <div id="combinationDiv" class="hidden">
          <label for="result_combination">Combination Code:</label>
          <input type="text" name="result_combination" id="result_combination" placeholder="e.g., PCM">
          <br><br>
        </div>

        <!-- Div where subject rows will be inserted -->
        <div id="subjectFields"></div>
      </div>

      <!-- One Submit Button for the Entire Combined Form -->
      <button type="submit" class="btn">Submit All Information</button>
    </form>
  </div>

  <script>
    // Toggle the visibility of the Student Results section (if needed)
    // For example, you might have a button that shows/hides this section.
    // Here we assume the section is always visible once the user selects a level.
    
    // When the education level in the student results section is selected,
    // display the appropriate interface.
    document.getElementById('result_education_level').addEventListener('change', function(){
      var level = this.value;
      var trackDiv = document.getElementById('trackSelection');
      var combDiv = document.getElementById('combinationDiv');
      var subjectFieldsDiv = document.getElementById('subjectFields');
      // Clear any previously loaded subject fields and reset hidden track field
      subjectFieldsDiv.innerHTML = "";
      document.getElementById('result_track').value = "";
      
      if (level === 'CSEE') {
        // For CSEE: Show track selection; hide combination code.
        trackDiv.classList.remove('hidden');
        combDiv.classList.add('hidden');
      } else if (level === 'ACSEE') {
        // For ACSEE: Hide track selection; show combination code.
        trackDiv.classList.add('hidden');
        combDiv.classList.remove('hidden');
        loadSubjectFieldsForACSEE();
      } else {
        trackDiv.classList.add('hidden');
        combDiv.classList.add('hidden');
      }
    });

    // When a track button is clicked (for CSEE), set the hidden input and load subject fields
    var trackButtons = document.querySelectorAll('.track-btn');
    trackButtons.forEach(function(button) {
      button.addEventListener('click', function(){
        var selectedTrack = this.getAttribute('data-track');
        document.getElementById('result_track').value = selectedTrack;
        loadSubjectFieldsForCSEE(selectedTrack);
      });
    });

    // Function to load subject rows for CSEE based on selected track.
    function loadSubjectFieldsForCSEE(track) {
      var subjectFieldsDiv = document.getElementById('subjectFields');
      var subjects = [];
      if (track === 'Arts') {
        subjects = ["History", "Geography", "Literature", "Economics", "Civic Education", "Religious Studies", "Fine Arts", "Music"];
      } else if (track === 'Science') {
        subjects = ["Physics", "Chemistry", "Biology", "Mathematics", "Geography", "English", "ICT", "Technical Drawing"];
      } else if (track === 'Business') {
        subjects = ["Accounting", "Business Studies", "Economics", "Mathematics", "English", "Geography", "ICT", "Entrepreneurship"];
      } else {
        subjects = ["Subject1", "Subject2", "Subject3", "Subject4", "Subject5", "Subject6", "Subject7", "Subject8"];
      }

      var html = "";
      for (var i = 0; i < subjects.length; i++) {
        var subj = subjects[i];
        html += '<div class="subject-row">';
        html += '<label>' + subj + ':</label> ';
        html += '<input type="text" name="subject' + (i+1) + '" value="' + subj + '" readonly> ';
        var grades = ['A', 'B', 'C', 'D', 'E', 'F'];
        grades.forEach(function(g) {
          html += '<label><input type="radio" name="grade' + (i+1) + '" value="' + g + '" required> ' + g + '</label> ';
        });
        html += '</div>';
      }
      subjectFieldsDiv.innerHTML = html;
    }

    // Function to load subject rows for ACSEE (manual input).
    function loadSubjectFieldsForACSEE() {
      var subjectFieldsDiv = document.getElementById('subjectFields');
      var html = "";
      for (var i = 1; i <= 8; i++) {
        html += '<div class="subject-row">';
        html += '<label>Subject ' + i + ':</label> ';
        html += '<input type="text" name="subject' + i + '" placeholder="Enter subject name" required> ';
        html += '<label> Grade:</label> ';
        html += '<input type="text" name="grade' + i + '" placeholder="e.g., A, B" maxlength="2" required>';
        html += '</div>';
      }
      subjectFieldsDiv.innerHTML = html;
    }
  </script>
</body>
</html>
