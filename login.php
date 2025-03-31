<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body style="display:grid; place-items:center;">
    <!--Header-->
    <div class="logo" style="justify-self = center">Bartr</div>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 405px; height: 405px" src="images\login image.jpg" />
    <div style="align-self: stretch; height: 254px; position: relative">
    <br><br>
    <!-- Login Form -->
    <form class = "center" action= "processLogin.php" method="post" style="width: 300px; margin:auto; position: relative;">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" class="login_button" name="login" value="Log In"/>
        </form>
    </div>
</body>
</html>
