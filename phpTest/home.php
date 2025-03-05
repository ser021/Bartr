<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <!--Header Container-->
    <div style="width: 100%; height: 91px;">
        <div style="width: 100%; height: 90px; left: 0px; top: 1px; position: absolute; border-bottom: 1px #FFE599 solid"></div>
        <!--Profile Button-->
        <button class="login_button" onclick="goToProfile()" style="right: 200px; top:19px; position: absolute;">Profile</button>
        <!--Messages Button-->
        <button class="login_button" onclick="goToMessages()" style="right: 37px; top:19px; position: absolute;">Messages</button>
        <!--Logo-->
        <div style="width: 185px; height: 73px; left: 37px; top: 0px; position: absolute; color: #FFE599; font-size: 80px; font-family: Castoro; font-weight: 400; word-wrap: break-word">Bartr</div>
    </div>
    <!--Post Container-->

    <script>
        function goToProfile() {
            // Navigate to the login page
            window.location.href = "profile.php";
        }
        function goToMessages() {
            // Navigate to Registration
            window.location.href = "messages.php"
        }
    </script>
</body>
</html>
