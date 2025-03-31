<?php 
    session_start();
    require "functions.php";
    $user_data = checkLogin();
?>
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
        <!-- ONLY THE FIRST NEEDS TO BE TURNED INTO LOOP, THE POSTS AFTER ARE FOR DEMO PURPOSES -->
        <div class="postholder">
            <!-- PHP loop that connects to the database and gets 8 distinct posts -->
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
                    <?php $op_info = getOwnerProfileByListing($listing['owner_id'])?>
                    <img width="82px" height="82px" src="<?php echo "{$op_info[0]['profile_pic']}"?>" alt="" style="object-fit:cover;">
                </div>
                <div class="title">
                    <!-- Turns into variable for title from listings table -->
                    <a class="title" href="singlelisting.php?title=<?php echo urlencode($listing['title']) ?>"><?php echo($listing['title'])?></a>
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
