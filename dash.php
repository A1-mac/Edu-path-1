<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database (Replace with actual database query)
include('conn.php');
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


$query = "SELECT COUNT(*) as total FROM universities";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$hasData = $row['total'] > 0;

$query = "SELECT COUNT(*) as total FROM courses";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$hasCourses = $row['total'] > 0;



// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
include 'navbar.php';
?>
    <div class="sidebar">
        <h3>Edu Path</h3>
        <ul class="sidebar-menu">
        <li class="active"><a href="dash.php">Dashboard</a></li>
        <li><a href="home.php">User Profile</a></li>
        <li><a href="set.php">Settings</a></li>
    </ul>
    </div>

    <div class="main-content">
        <div class="container" style="display: block; margin-top: -290px; margin-left: -400px; position: absolute;">
            <div class="card">
            <?php
// Database connection
$conn = new mysqli('localhost', 'mac', 'pass', 'Edupath_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's academic results from the database
$user_id = 1; // Assume user ID is 1, modify as needed
$sql_results = "SELECT subject_name, grade FROM student_results WHERE user_id = $user_id";
$result_set = $conn->query($sql_results);

$pass_count = 0;
$subject_results = [
    'math' => false,
    'english' => false,
    'science' => false,
    'trade_test' => false,
    'nva' => false,
];

// Define the passing grade threshold (adjust as needed)
$passing_grades = ['A', 'B', 'C'];

if ($result_set->num_rows > 0) {
    while ($row = $result_set->fetch_assoc()) {
        if (in_array($row['grade'], $passing_grades)) {
            $pass_count++;
        }
        
        if ($row['subject_name'] === 'Mathematics' && in_array($row['grade'], $passing_grades)) {
            $subject_results['math'] = true;
        }
        if ($row['subject_name'] === 'English' && in_array($row['grade'], $passing_grades)) {
            $subject_results['english'] = true;
        }
        if ($row['subject_name'] === 'Science' && in_array($row['grade'], $passing_grades)) {
            $subject_results['science'] = true;
        }
    }
}

// Echo total passes
echo "$pass_count pass(es)";

// Pagination setup
$limit = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $limit;

// Fetch courses and admission requirements
$sql_courses = "SELECT c.program_name, u.name AS university_name, c.admission_requirements 
                FROM courses c
                INNER JOIN universities u ON c.university_name = u.name";
$result_courses = $conn->query($sql_courses);

$eligible_courses = [];

if ($result_courses->num_rows > 0) {
    while ($row = $result_courses->fetch_assoc()) {
        $requirements = strtolower($row['admission_requirements']);
        $eligible = true;

        // Define requirement conditions
        $conditions = [
            "at least $pass_count passes" => ($pass_count >= intval($pass_count)),
            "basic mathematics" => $subject_results['math'],
            "english language" => $subject_results['english'],
            "science subject" => $subject_results['science'],
        ];

        foreach ($conditions as $requirement => $is_met) {
            if (strpos($requirements, $requirement) !== false && !$is_met) {
                $eligible = false;
                break;
            }
        }

        if ($eligible) {
            $eligible_courses[] = $row;
        }
    }
}

$total_courses = count($eligible_courses);
$total_pages = ceil($total_courses / $limit);
$paginated_courses = array_slice($eligible_courses, $offset, $limit);

// Display results
if (!empty($paginated_courses)) {
    echo "<h3>Courses That You're Eligible For</h3>";
    echo "<table border='1' class='notifications-table'>";
    echo "<thead><tr><th>Program Name</th><th>University</th></tr></thead><tbody>";
    
    foreach ($paginated_courses as $course) {
        echo "<tr><td>" . htmlspecialchars($course['program_name']) . "</td>";
        echo "<td>" . htmlspecialchars($course['university_name']) . "</td></tr>";
    }
    
    echo "</tbody></table>";
} else {
    echo "<p>No eligible courses found.</p>";
}

// Pagination UI
if ($total_pages > 1) {
    echo "<div class='pagination'>";
    if ($current_page > 1) {
        echo "<a href='?page=" . ($current_page - 1) . "'>&laquo; Prev</a>";
    }
    for ($page = 1; $page <= $total_pages; $page++) {
        echo $page == $current_page ? "<a href='?page=$page' class='active'>$page</a>" : "<a href='?page=$page'>$page</a>";
    }
    if ($current_page < $total_pages) {
        echo "<a href='?page=" . ($current_page + 1) . "'>Next &raquo;</a>";
    }
    echo "</div>";
}

$conn->close();
?>


            </div>

            <div class="card ">
                <h3>Universities Registered in Tanzania</h3>
               <!-- Display the "Fetch Universities" button only if the table is empty -->
                <?php if (!$hasData): ?>
                    <form action="upload_csv.php" method="POST">
                        <button type="submit" class="btn">Fetch Universities</button>
                    </form>
                <?php endif; ?>

                <?php if (!$hasCourses): ?>
                    <form action="fetch_courses.php" method="POST">
                        <button type="submit" class="btn">Fetch Universities Courses</button>
                    </form>
                <?php endif; ?>
                <br>
                <form method="GET" action="" style="display: flex; justify-content: flex-end;">
                    <input type="search" class="search-input" name="search_query" id="search_query" placeholder="Search by name or location">
                    <button type="submit" class="search-button" >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg></button>
                </form>
                <br>
                <br>
                    <br>
                <!-- Table for Displaying Universities -->
                <table class="notifications-table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Head Office</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display universities from the database
                        $conn = new mysqli('localhost', 'mac', 'pass', 'Edupath_db');
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        
                          // Records per page
                        $records_per_page = 10;

                        // Current page number
                        $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

                        // Calculate total records
                        $total_records_query = "SELECT COUNT(*) AS total FROM universities";
                        $total_records_result = $conn->query($total_records_query);
                        $total_records_row = $total_records_result->fetch_assoc();
                        $total_records = $total_records_row['total'];


                        // Calculate total pages
                        $total_pages = ceil($total_records / $records_per_page);

                        // Calculate offset for the query
                        $offset = ($current_page - 1) * $records_per_page;

                        // Initialize search query
                        $search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

                        // Base query
                        $query = "SELECT sn, name, head_office, type, status, action FROM universities";

                        // Append search condition if search_query is provided
                        if (!empty($search_query)) {
                            $query .= " WHERE name LIKE ? OR head_office LIKE ? ORDER BY sn ASC";
                            $stmt = $conn->prepare($query);
                            $search_param = "%" . $search_query . "%";
                            $stmt->bind_param("ss", $search_param, $search_param);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        } else {
                            $query .= " ORDER BY CAST(sn AS UNSIGNED) ASC LIMIT $offset, $records_per_page";
                            $result = $conn->query($query);
                        }

                        // Check and display results
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['sn']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['head_office']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td><a href='" . htmlspecialchars($row['action']) . "' target='_blank'>View</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No results found.</td></tr>";
                        }

                        // Close connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <div class="pagination">
        <?php
        // Previous button
        if ($current_page > 1) {
            echo "<a href='?page=" . ($current_page - 1) . "&search_query=" . urlencode($search_query) . "'>&laquo; Prev</a>";
        }

        // Page numbers
        for ($page = 1; $page <= $total_pages; $page++) {
            if ($page == $current_page) {
                echo "<a href='?page=$page&search_query=" . urlencode($search_query) . "' class='active'>$page</a>";
            } else {
                echo "<a href='?page=$page&search_query=" . urlencode($search_query) . "'>$page</a>";
            }
        }

        // Next button
        if ($current_page < $total_pages) {
            echo "<a href='?page=" . ($current_page + 1) . "&search_query=" . urlencode($search_query) . "'>Next &raquo;</a>";
        }
        ?>
    </div>

            </div>

            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const profileImage = document.getElementById("profileImage");
        const profilePicture = document.getElementById("profilePicture");
        const uploadInput = document.getElementById("uploadProfileImage");
        const initials = document.getElementById("initials");

        // Generate a random color for the profile background
        const randomColor = () => {
            const colors = [
                "#4a90e2", "#50e3c2", "#f5a623", "#e94e77", "#bd10e0", "#b8e986"
            ];
            return colors[Math.floor(Math.random() * colors.length)];
        };

        // Check if the user has a profile image
        const hasProfileImage = profileImage.src && !profileImage.src.includes("placeholder.com");
        if (hasProfileImage) {
            initials.style.display = "none"; // Hide initials if an image exists
            profileImage.style.display = "block"; // Show profile image
        } else {
            profilePicture.style.backgroundColor = randomColor(); // Set random background color
        }

        // Handle click to upload a new image
        profilePicture.addEventListener("click", () => {
            uploadInput.click(); // Trigger file input
        });

        // Update profile image when a new image is selected
        uploadInput.addEventListener("change", event => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    profileImage.src = reader.result; // Update the image source
                    profileImage.style.display = "block"; // Show the new image
                    initials.style.display = "none"; // Hide initials
                };
                reader.readAsDataURL(file);
            }
        });
    });

    document.addEventListener("DOMContentLoaded", () => {
            // Dropdown logic
            const userProfile = document.getElementById("userProfile");
            const userDropdown = document.getElementById("userDropdown");

            userProfile.addEventListener("click", () => {
                userDropdown.classList.toggle("active");
            });

            // Close dropdown when clicking outside
            document.addEventListener("click", (event) => {
                if (!userProfile.contains(event.target)) {
                    userDropdown.classList.remove("active");
                }
            });

            // Sidebar navigation logic
            const sidebarLinks = document.querySelectorAll(".sidebar ul li a");
            sidebarLinks.forEach(link => {
                link.addEventListener("click", (event) => {
                    sidebarLinks.forEach(link => link.parentElement.classList.remove("active"));
                    event.target.parentElement.classList.add("active");
                });
            });
        });

        
    </script>
</body>
</html>
