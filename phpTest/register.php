<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body style="justify-content: center;">
    <!--Header-->
    <div class="logo" style="justify-self = center">Bartr</div>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 405px; height: 405px" src="images\pexels-karolina-grabowska-4239035.jpg" />
    <div style="align-self: stretch; height: 254px; position: relative">
    <br><br>
    <!-- Login Form -->
    <form class = "center" action= # style="width: 300px; margin:auto; position: relative;">
        <!-- Would need to write name to user table in db upon successful submission -->
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required><br><br>

        <!-- Would need to write email to user table in db upon successful submission -->
        <label for="email">Email</label>
        <input type="text" id="email" name="email" required><br><br>
    
        <!-- Would need to check list of usernames to be sure it isnt taken, then write username to user table in db upon successful submission -->
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <!-- Would need to write hashed password to user table in db upon successful submission -->
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit" class="login_button" onclick="goToHome()">Register</button>
        </form>
    </div>
    <script>
        function goToHome() {
            // Edit this to be go to home only if successfully filled form out
            window.location.href = "home.php";
        }
    </script>
</body>
</html>
