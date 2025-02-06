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

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Settings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- header -->
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

    <!-- sidebar -->
    <div class="sidebar">
        <h3>Edu Path</h3>
        <ul class="sidebar-menu">
        <li ><a href="dash.php">Dashboard</a></li>
        <li><a href="home.php">User Profile</a></li>
        <li><a href="credit.php">Accreditation</a></li>
        <li class="active"><a href="set.php">Settings</a></li>
    </ul>
    </div>
<!-- Main Container -->
<div class="main-content">
    <div class="container" style="display: block; margin-top: -290px; margin-left: -400px; position: absolute;">

        <!-- General Settings Section -->
        <div class="card appearance-settings">
            <h3>General Settings</h3>
            <form class="settings-form" id="generalSettingsForm">
                <label for="websiteName">Website Name:</label>
                <input type="text" id="websiteName" name="websiteName" placeholder="Enter website name" readonly/>

                <label for="defaultLanguage">Default Language:</label>
                <select id="defaultLanguage" onchange="changeLanguage(this.value)" name="defaultLanguage">
                    <option value="en">English</option>
                    <option value="sw">Swahili</option>
                    <option value="es">Spanish</option>
                </select>

                <i id="languageError" class="error-message"></i> 

                <label for="timezone">Timezone:</label>
                <select id="timezone" name="timezone">
                    <option value="utc">UTC</option>
                    <option value="pst">Pacific Standard Time</option>
                    <option value="est">Eastern Standard Time</option>
                </select>
                <i id="timezoneError" class="error-message"></i>

                <button type="button" id="saveGeneralSettings">Save Changes</button>
            </form>
        </div>

        <div id="google_translate_element" style="margin-top: 20px;"></div>
        <div class="card appearance-settings">
            <h3>Appearance</h3>
            <form class="settings-form">
                <label for="theme">Theme:</label>
                <select id="theme" name="theme">
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                    <option value="custom">Custom</option>
                </select>

                <button type="submit">Save Appearance Settings</button>
            </form>
        </div>

        <!-- Server Configuration Section -->
        <div class="card appearance-settings">
            <h3>Server Configuration</h3>
            <form class="settings-form">
                <label for="portNumber">Port Number:</label>
                <input type="number" id="portNumber" name="portNumber" placeholder="Enter server port" value="8080" />

                <label for="serverMode">Server Mode:</label>
                <select id="serverMode" name="serverMode">
                    <option value="development">Development</option>
                    <option value="production">Production</option>
                </select>

                <label for="logLevel">Log Level:</label>
                <select id="logLevel" name="logLevel">
                    <option value="info">Info</option>
                    <option value="debug">Debug</option>
                    <option value="error">Error</option>
                </select>

                <button type="submit">Update Server Settings</button>
            </form>
        </div>

        <!-- File Management Section -->
        <div class="card appearance-settings">
            <h3>File Management</h3>
            <form class="settings-form">
                <label for="uploadDir">Upload Directory:</label>
                <input type="text" id="uploadDir" name="uploadDir" placeholder="Enter upload directory path" />

                <label for="maxUploadSize">Max Upload Size (MB):</label>
                <input type="number" id="maxUploadSize" name="maxUploadSize" placeholder="Enter maximum upload size" value="10" />

                <button type="submit">Save File Settings</button>
            </form>
        </div>

        <!-- Maintenance Mode -->
        <div class="card appearance-settings">
            <h3>Maintenance Mode</h3>
            <form class="settings-form">
                <label>
                    <input type="checkbox" name="maintenanceMode" />
                    Enable Maintenance Mode
                </label>

                <button type="submit">Activate</button>
            </form>
        </div>

        <!-- Backup and Restore Section -->
        <div class="card appearance-settings">
            <h3>Backup and Restore</h3>
            <form class="settings-form">
                <button type="button" onclick="backupWebsite()">Backup Website</button>

                <label for="restoreFile">Restore from Backup:</label>
                <input type="file" id="restoreFile" name="restoreFile" accept=".zip,.tar" />

                <button type="submit">Restore</button>
            </form>
        </div>

        <!-- Security Settings -->
        <div class="card appearance-settings">
            <h3>Security Settings</h3>
            <form class="settings-form">
                <label>
                    <input type="checkbox" name="enableFirewall" checked />
                    Enable Firewall
                </label>

                <label>
                    <input type="checkbox" name="disableDirectoryBrowsing" checked />
                    Disable Directory Browsing
                </label>

                <button type="submit">Update Security Settings</button>
            </form>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const websiteNameInput = document.getElementById("websiteName");
        const websiteDisplay = document.getElementById("websiteDisplay");
        const defaultLanguage = document.getElementById("defaultLanguage");
        const saveButton = document.getElementById("saveGeneralSettings");

        // Default website name
        const websiteName = "Edu Path";
        websiteNameInput.value = websiteName;
        websiteDisplay.textContent = websiteName;

        // Save Changes button functionality
        saveButton.addEventListener("click", (event) => {
            event.preventDefault(); // Prevent form submission

            const selectedLanguage = defaultLanguage.value;
            changeLanguage(selectedLanguage);
        });

        // Function to change the language
        function changeLanguage(languageCode) {
            const googleTranslateFrame = document.querySelector(
                'iframe.goog-te-menu-frame'
            );

            if (googleTranslateFrame) {
                // Get the dropdown from the Google Translate iframe
                const translateItems = googleTranslateFrame.contentDocument.body.querySelectorAll(
                    '.goog-te-menu2-item span.text'
                );

                // Find and click the corresponding language in the dropdown
                Array.from(translateItems).forEach((item) => {
                    if (item.textContent.toLowerCase().includes(languageCode)) {
                        item.click();
                    }
                });
            } else {
                alert('Google Translate widget not loaded yet. Please try again.');
            }
        }

        // Add Google Translate integration
        const addGoogleTranslate = () => {
            new google.translate.TranslateElement(
                {
                    pageLanguage: "en", // Default language
                    includedLanguages: "en,sw,es", // English, Swahili, Spanish
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                    autoDisplay: false,
                },
                "google_translate_element"
            );
        };

        // Dynamically load Google Translate script
        const translateScript = document.createElement("script");
        translateScript.type = "text/javascript";
        translateScript.src =
            "//translate.google.com/translate_a/element.js?cb=addGoogleTranslate";
        document.body.appendChild(translateScript);
    });
</script>



<script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("generalSettingsForm");
            const websiteNameInput = document.getElementById("websiteName");
            const defaultLanguage = document.getElementById("defaultLanguage");
            const timezone = document.getElementById("timezone");
            const saveButton = document.getElementById("saveGeneralSettings");

            const websiteNameError = document.getElementById("websiteNameError");
            const languageError = document.getElementById("languageError");
            const timezoneError = document.getElementById("timezoneError");

            saveButton.addEventListener("click", () => {
                websiteNameError.textContent = "";
                languageError.textContent = "";
                timezoneError.textContent = "";

                let hasError = false;

                if (!websiteNameInput.value.trim()) {
                    websiteNameError.textContent = "Website name cannot be empty.";
                    hasError = true;
                }

                if (!defaultLanguage.value) {
                    languageError.textContent = "Please select a default language.";
                    hasError = true;
                }

                if (!timezone.value) {
                    timezoneError.textContent = "Please select a timezone.";
                    hasError = true;
                }

                if (!hasError) {
                    alert("Settings saved successfully!");
                }
            });
        });
    </script>

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
