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
    <img style="display: block; margin-left: auto; margin-right: auto; width: 405px; height: 405px" src="images\login image.jpg" />
    <div style="align-self: stretch; height: 254px; position: relative">
    <br><br>
    <!-- Login Form -->
    <form class = "center" action= # style="width: 300px; margin:auto; position: relative;">
        <!-- Needs to search for username in db -->
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required><br><br>

        <!-- Needs to search for pw in database, also need to work out password hashing -->
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit" class="login_button" onclick="goToHome()">Log In</button>
        </form>
    </div>
    <script>
        function goToHome() {
            // Navigate to the home page, change later to be only upon successful login
            window.location.href = "home.php";
        }
    </script>
</body>
</html>
