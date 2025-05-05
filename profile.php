<?php 
    session_start();
    require "functions.php";

    if (!isset($_SESSION['user_id'])) {
        echo "You need to log in first.";
        exit;
    }

    $logged_in_user = checkLogin();
    if ($logged_in_user === null) {
        echo "No user data found. Check login process.";
        exit;
    }

    // Check if visiting someone else's profile
    if (isset($_GET['user_id'])) {
        $profile_user_id = (int)$_GET['user_id'];
        $profile_data = getProfileById($profile_user_id);

        if (!$profile_data) {
            echo "User not found.";
            exit;
        }
        $user_listings = getListingsByProfile($profile_user_id);
    } else {
        // Viewing own profile
        $profile_data = $logged_in_user;
        $user_listings = getListingsByProfile($logged_in_user['id']);
    }

    $is_own_profile = ($profile_data['id'] === $logged_in_user['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <div class="logo">Bartr</div>
        <div class="links">
            <a href="home.php">Home</a>
            <a href="messages.php">Messages</a>
            <a href="addPost.php">Add Post</a>
        </div>
    </nav>

    <main style="display:grid; place-items:center;">
        <!-- PROFILE INFORMATION -->
        <div class="profileparent">
            <div class="profilepic">
                <img width="200px" height="200px" src="<?= htmlspecialchars($profile_data['profile_pic']) ?>" alt="" style="object-fit:cover;">
            </div>
            <div class="username">
                <?= htmlspecialchars($profile_data['username']) ?>
                <div class="description">
                    <?= htmlspecialchars($profile_data['bio']) ?>
                </div>

                <?php if ($is_own_profile): ?>
                    <button class="login_button" onclick="window.location.href='editProfile.php'">Edit Profile</button>
                <?php endif; ?>
            </div>
        </div>

        <!-- POSTS -->
        <div class="postholder">
            <?php if (!empty($user_listings)):
                foreach($user_listings as $listing): ?>
            <div class="postgrid" width="385px">
                <div class="listingimage" style="border-top-left-radius: 8px; border-top-right-radius: 8px;">
                    <img width="385px" height="275px" src="<?= htmlspecialchars($listing['images']) ?>" alt="" style="object-fit:cover;">
                </div>
                <div class="profilepic">
                    <img width="82px" height="82px" src="<?= htmlspecialchars($profile_data['profile_pic']) ?>" alt="" style="object-fit:cover;">
                </div>
                <div class="title">
                    <a class="title" href="singlelisting.php?title=<?= urlencode($listing['title']) ?>"><?= htmlspecialchars($listing['title']) ?></a>
                </div>
                <div class="description">
                    <?= htmlspecialchars($listing['description']) ?>
                </div>
            </div>
            <?php endforeach; else: ?>
            <p style="justify-self:center;">No listings found for this user.</p>
        <?php endif; ?><br>
        </div>
    </main>

</body>
</html>
