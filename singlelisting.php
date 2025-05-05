<?php 
    session_start();
    require "functions.php";
    $user_data = checkLogin();
    if(isset($_GET['title'])){
        $title = urldecode($_GET['title']);
    }else{
        header("Location: home.php");
        exit();
    }
    $listing = getListingByTitle($title);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <div class="logo">Bartr</div>
        <div class="links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="messages.php">Messages</a>
            <a href="addPost.php">Add Post</a>
        </div>
    </nav>

    <main style="display:grid; place-items:center;">
        <div class="rightholder" class="center" style="padding-top: 50px;">
            <!-- need to turn into something that connects to db to display accurate post information and images -->
            <div class="image" style="padding-right:30px;">
                <img width="615px" height="615px" style="object-fit:cover;" src="<?php echo "{$listing[0]['images']}"?>" alt="">
            </div>
            <div class="pfp">
                <?php $op_info = getOwnerProfileByListing($listing[0]['owner_id'])?>
                <a href="profile.php?user_id=<?= $listing[0]['owner_id'] ?>">
                    <img width="100px" height="100px" style="object-fit:cover; cursor: pointer;" src="<?= htmlspecialchars($op_info[0]['profile_pic']) ?>" alt="Profile Picture">
                </a>
            </div>
            <div class="title"> <?php echo($listing[0]['title'])?> </div>
            <div class="description"><?php echo($listing[0]['description'])?></div><br>
            <div class="button-container">
                <button class="messagebtn" onclick="window.location.href='messages.php'">Message</button>
                <?php if ((int)$user_data['id'] === (int)$listing[0]['owner_id']): ?>
                    <form method="POST" action="deletePost.php" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        <input type="hidden" name="post_id" value="<?= $listing[0]['id'] ?>">
                        <button type="submit" class="deletebtn">Delete Post</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>
</html>
