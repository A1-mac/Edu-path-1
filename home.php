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

$user_id = $_SESSION['user_id']; // Replace with dynamic user ID if needed
$query = "SELECT education_level, division, preferred_combination_1, grade1, 
                 preferred_combination_2, grade2, preferred_combination_3, grade3 
          FROM user_education 
          WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize variables
$education_level = '';
$division = '';
$table_rows = '';

// Process the result
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $education_level = htmlspecialchars($row['education_level']);
    $division = htmlspecialchars($row['division']);

    // Populate table rows
    $table_rows .= "
        <tr>
            <td>" . htmlspecialchars($row['preferred_combination_1']) . "</td>
            <td>" . htmlspecialchars($row['grade1']) . "</td>
            <td>" . getGradeDescription($row['grade1']) . "</td>
        </tr>
        <tr>
            <td>" . htmlspecialchars($row['preferred_combination_2']) . "</td>
            <td>" . htmlspecialchars($row['grade2']) . "</td>
            <td>" . getGradeDescription($row['grade2']) . "</td>
        </tr>
        <tr>
            <td>" . htmlspecialchars($row['preferred_combination_3']) . "</td>
            <td>" . htmlspecialchars($row['grade3']) . "</td>
            <td>" . getGradeDescription($row['grade3']) . "</td>
        </tr>";
} else {
    $table_rows = "<tr><td colspan='3'>No results found.</td></tr>";
}

$stmt->close();

// Close the database connection
$conn->close();

function getGradeDescription($grade) {
    switch (strtoupper($grade)) {
        case 'A': return 'Excellent';
        case 'B+': return 'Very Good';
        case 'B': return 'Good';
        case 'C': return 'Average';
        case 'D': return 'Below Average';
        case 'F': return 'Fail';
        default: return 'Unknown';
    }
}

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
        <li class="active"><a href="home.php">User Profile</a></li>
        <li><a href="credit.php">Accreditation</a></li>
        <li><a href="set.php">Settings</a></li>
    </ul>
    </div>

    <div class="main-content">
        <div class="container" style="margin-top: 140px;">
            <div class="card">
            <div class="user-profile">
    <div class="profile-picture" 
        id="profilePicture" 
        style="background-color: <?php echo empty($user['profile_image']) || $user['profile_image'] === 'initial' ? '#4a90e2' : 'transparent'; ?>;">
        
        <?php if (!empty($user['profile_image']) && $user['profile_image'] !== 'initial'): ?>
            <!-- Display the profile picture -->
            <img id="profileImage" 
                src="<?php echo htmlspecialchars($user['profile_image']); ?>" 
                alt="Profile Picture" 
                style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; display: block;" />
        <?php else: ?>
            <!-- Display user initials -->
            <span id="initials" 
                style="display: flex; justify-content: center; align-items: center; 
                       width: 100px; height: 100px; font-size: 24px; 
                       color: white; border-radius: 50%; text-transform: uppercase;">
                <?php 
                $nameParts = explode(' ', trim($user['name']));
                $initials = '';
                if (!empty($nameParts[0])) {
                    $initials .= strtoupper(substr($nameParts[0], 0, 1)); // First initial
                }
                if (!empty($nameParts[1])) {
                    $initials .= strtoupper(substr($nameParts[1], 0, 1)); // Second initial
                }
                echo $initials;
                ?>
            </span>
        <?php endif; ?>

        <div class="hover-overlay" style="text-align: center; margin-top: 10px;">
            <p>Change Image</p>
        </div>
    </div>
                    <!-- Form to handle file upload -->
                    <form method="POST" action="upload_profile_image.php" enctype="multipart/form-data">
                        <!-- Hidden File Input for Upload -->
                        <input type="file" name="profile_image" id="uploadProfileImage" accept="image/*" style="display: none;" />
                        <button type="submit" class="btn">Upload</button>
                    </form>

                    <div class="user-info" id="userInfo">
                        <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>
                        <button class="btn" id="updateButton">Update Information</button>
                    </div>
                                        <!-- Hide the form by default -->
                    <form class="user-form" id="updateForm" method="POST" action="update_user.php" style="display: none; margin-right: 100px;">
                        <label for="name">Name:</label>
                        <input type="text" id="name" class="inputst" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        <i style="color:#d61d1d">
                            <?php 
                            include 'update_user.php';
                            echo $fullnameError; ?>
                        </i><br><br>
                        <label for="email">Email:</label>
                        <input type="email" id="email" class="inputst" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <i style="color:#d61d1d">
                            <?php 
                            include 'update_user.php';
                            echo $emailError; ?>
                        </i><br><br>
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" class="inputst" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        <i style="color:#d61d1d">
                            <?php 
                            include 'update_user.php';
                            echo $phoneError; ?>
                        </i><br><br>
                        <button type="submit" class="btn">Submit</button>
                        <button type="button" class="btn btn-clear" id="cancelButton">Cancel</button>
                    </form>
                </div>
            </div>

            <div class="card appearance-settings">
                <h3>Preferred Combination and their results</h3>

                    <!-- Display Division and Education Level -->
                <p><strong>Division:</strong> <?php echo $division ? "Division $division" : 'N/A'; ?></p>
                <p><strong>Education Level:</strong> <?php echo $education_level ? $education_level : 'N/A'; ?></p>


                <table class="notifications-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Range</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php echo $table_rows; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <a href="#">&laquo; Prev</a>
                    <a href="#">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">Next &raquo;</a>
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
