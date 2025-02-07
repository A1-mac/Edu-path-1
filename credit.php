<?php
// accreditation.php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('conn.php');
$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Get search query from URL, if any.
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get the current page number (default 1)
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) {
    $current_page = 1;
}

// Set the number of records per page and calculate offset
$limit = 10;
$offset = ($current_page - 1) * $limit;

// Count the total matching records for pagination.
$count_sql = "SELECT COUNT(*) AS total 
              FROM accreditation a
              JOIN universities u ON a.uni_id = u.id";
if (!empty($search)) {
    $count_sql .= " WHERE u.name LIKE ?";
}
$stmt = $conn->prepare($count_sql);
if (!empty($search)) {
    $param = "%" . $search . "%";
    $stmt->bind_param("s", $param);
}
$stmt->execute();
$count_result = $stmt->get_result();
$row = $count_result->fetch_assoc();
$total_records = $row['total'];
$total_pages = ceil($total_records / $limit);
$stmt->close();

// Fetch the accreditation data with university names.
$data_sql = "SELECT a.uni_id, u.name AS university_name, a.best_courses
             FROM accreditation a
             JOIN universities u ON a.uni_id = u.id";
if (!empty($search)) {
    $data_sql .= " WHERE u.name LIKE ?";
}
$data_sql .= " ORDER BY u.name LIMIT ? OFFSET ?";
$stmt = $conn->prepare($data_sql);
if (!empty($search)) {
    $param = "%" . $search . "%";
    $stmt->bind_param("sii", $param, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - User Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">Edu Path</div>
        <div class="user-profile" id="userProfile">
                <!-- Profile Picture Logic -->
            <div class="profile-picture"  id="headerProfilePicture" 
                style="background-color: <?php echo empty($user['profile_image']) || $user['profile_image'] === 'initial' ? '#4a90e2' : 'transparent'; ?>;">

                <?php if (!empty($user['profile_image']) && $user['profile_image'] !== 'initial'): ?>
                    <!-- Display the profile picture -->
                    <img id="headerProfileImage"  src="<?php echo htmlspecialchars($user['profile_image']); ?>" 
                        alt="Profile Picture" style="display: block;" />
                <?php else: ?>
                    <!-- Display user initials -->
                    <span id="headerInitials">
                        <?php echo strtoupper(substr($user['name'], 0, 1) . (strstr($user['name'], ' ') ? substr(strstr($user['name'], ' '), 1, 1) : '')); ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="user-dropdown" id="userDropdown">
                <div class="info">
                    <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <a href="home.php">Manage Your Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Edu Path</h3>
        <ul class="sidebar-menu">
        <li><a href="dash.php">Dashboard</a></li>
        <li ><a href="home.php">User Profile</a></li>
        <li class="active"><a href="credit.php">Accreditation</a></li>
        <li><a href="set.php">Settings</a></li>
        
    </ul>
    </div>

    <div class="main-content">
        <div class="container" style="display: block; margin-top: -290px; margin-left: -400px; position: absolute;">
            <div class="card">
            <div class="card appearance-settings">
            <h2>Accreditation Table</h2>
        <!-- Search Form -->
        <form method="GET" action="" style="display: flex; justify-content: flex-end;">
                    <input type="search" class="search-input" name="search" id="search_query" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by University Name">
                    <button type="submit" class="search-button" value="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg></button>
                </form>
                    <br>
                 <!-- Accreditation Data Table -->
        <table class="notifications-table">
            <thead>
                <tr>
                    <th>University Name</th>
                    <th>Best At Courses</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['university_name']) . "</td>";
                        echo "<td>" . htmlspecialchars(str_replace(['[', ']', '"'], '', $row['best_courses'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No results found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

                 <!-- Pagination -->
        <?php
        if ($total_pages > 1) {
            echo "<div class='pagination'>";
            if ($current_page > 1) {
                echo "<a href='?page=" . ($current_page - 1) . "&search=" . urlencode($search) . "'>&laquo; Prev</a>";
            }
            for ($page = 1; $page <= $total_pages; $page++) {
                if ($page == $current_page) {
                    echo "<a href='?page=$page&search=" . urlencode($search) . "' class='active'>$page</a>";
                } else {
                    echo "<a href='?page=$page&search=" . urlencode($search) . "'>$page</a>";
                }
            }
            if ($current_page < $total_pages) {
                echo "<a href='?page=" . ($current_page + 1) . "&search=" . urlencode($search) . "'>Next &raquo;</a>";
            }
            echo "</div>";
        }
        ?>
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

        document.addEventListener("DOMContentLoaded", () => {
    const updateButton = document.getElementById("updateButton");
    const updateForm = document.getElementById("updateForm");
    const userInfo = document.getElementById("userInfo");

    // Add click event to the update button
    updateButton.addEventListener("click", () => {
        // Hide user info and show the update form
        userInfo.style.display = "none";
        updateForm.style.display = "block";
    });
});

const cancelButton = document.getElementById("cancelButton");

cancelButton.addEventListener("click", () => {
    // Show user info and hide the update form
    userInfo.style.display = "block";
    updateForm.style.display = "none";
});


document.addEventListener("DOMContentLoaded", () => {
    const profilePicture = document.getElementById("profilePicture");
    const uploadInput = document.getElementById("uploadProfileImage");

    profilePicture.addEventListener("click", () => {
        uploadInput.click(); // Trigger file input click
    });
});

    </script>
</body>
</html>
