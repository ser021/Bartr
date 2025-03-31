<?php 
    session_start();
    require "functions.php";
    if (!isset($_SESSION['user_id'])) {
        echo "You need to log in first.";
        exit;
    }
    $user_data = checkLogin();
    if ($user_data === null) {
        echo "No user data found. Check login process.";
        exit; // Exit to prevent further execution
    }
    // fetch listings of the logged-in user
    $user_listings = getListingsByProfile($user_data['id']);
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
        </div>
    </nav>
    <main style="display:grid; place-items:center;">
        <!-- PROFILE INFORMATION -->
        <div class="profileparent">
            <div class="profilepic">
            <img width="200px" height="200px" src="<?= $user_data['profile_pic']?>" alt="" style="object-fit:cover;">
            </div>
            <div class="username">
                <?= $user_data['username'] ?>
                <div class="description">
                    <?= $user_data['bio'] ?>
                </div>
            </div>
        </div>
        <!-- POSTS -->
        <div class="postholder">
            <?php if(!empty($user_listings)):
                foreach($user_listings as $listing): ?>
            <div class="postgrid" width="385px">
                <div class="listingimage" style = "border-top-left-radius: 8px; border-top-right-radius: 8px;">
                    <!-- Turns into image path variable from listings table -->
                    <img width="385px" height="275px" src="<?php echo "{$listing['images']}" ?>" alt="" style="object-fit:cover;">
                </div>
                <div class="profilepic">
                    <!-- Turns into image path variable from profile table -->
                    <img width="82px" height="82px" src="<?= $user_data['profile_pic']?>" alt="" style="object-fit:cover;">
                </div>
                <div class="title">
                    <!-- Turns into variable for title from listings table -->
                    <a class="title" href="singlelisting.php"><?php echo($listing['title'])?></a>
                </div>
                <div class="description">
                    <!-- Turns into variable for description from listings table -->
                    <?php echo($listing['description'])?>
                </div>
            </div>
            <?php endforeach; else: ?>
            <p style="justify-self:center;">No listings found for this user.</p>
        <?php endif;?><br>
        </div>
    </main>

</body>
</html>