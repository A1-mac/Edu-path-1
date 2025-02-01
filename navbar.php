
<div class="header">
    <div class="logo">Edu Path</div>
    <div class="user-profile" id="userProfile">
        <!-- Profile Picture Logic -->
        <div class="profile-picture" id="headerProfilePicture" 
            style="background-color: <?php echo empty($user['profile_image']) || $user['profile_image'] === 'initial' ? '#4a90e2' : 'transparent'; ?>;">

            <?php if (!empty($user['profile_image']) && $user['profile_image'] !== 'initial'): ?>
                <!-- Display the profile picture -->
                <img id="headerProfileImage" src="<?php echo htmlspecialchars($user['profile_image']); ?>" 
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
                <h4><?php echo htmlspecialchars($user['name'] ?? 'Guest'); ?></h4>
                <p><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
            </div>
            <a href="home.php">Manage Your Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>
