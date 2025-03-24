<?php require "functions.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <div class="logo">Bartr</div>
        <div class="links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="messages.php">Messages</a>
        </div>
    </nav>

    <main>
        <div class="postholder">
            <!-- PHP that connects to the database and gets 8 distinct posts -->
            <?php $listings = getListings(8) ?>
                <!-- PHP loop that separates the array into different posts for each row -->
                <?php
	                foreach ($listings as $listing) {
                        ?>
            <div class="postgrid" width="385px">
                <div class="listingimage" style = "border-top-left-radius: 8px; border-top-right-radius: 8px;">
                    <!-- Turns into image path variable from listings table -->
                    <img width="385px" height="275px" src="<?php echo "{$listing['images']}" ?>" alt="" style="object-fit:cover;">
                </div>
                <div class="profilepic">
                    <!-- STILL NEED TO DO Turns into image path variable from profile table -->
                    <img width="82px" height="82px" src="profilepictures/profile pic 1.jpg" alt="" style="object-fit:cover;">
                </div>
                <div class="title">
                    <!-- Turns into variable for title from listings table -->
                    <?php echo($listing['title'])?>
                </div>
                <div class="description">
                    <!-- Turns into variable for description from listings table -->
                    <?php echo($listing['description'])?>
                </div>
            </div>
        <?php } ?><br>
        </div>
    </main>

</body>
</html>
