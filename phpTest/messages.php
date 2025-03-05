<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT sender_id, message, created_at FROM messages WHERE recipient_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body style="background-color: #073763;">
    <!--Header Container-->
    <div style="width: 100%; height: 91px;">
        <div style="width: 100%; height: 90px; left: 0px; top: 1px; position: absolute; border-bottom: 1px #FFE599 solid"></div>
        <!--Profile Button-->
        <button class="login_button" onclick="goToProfile()" style="right: 200px; top:19px; position: absolute;">Profile</button>
        <!--Logo-->
        <div style="width: 185px; height: 73px; left: 37px; top: 0px; position: absolute; color: #FFE599; font-size: 80px; font-family: Castoro; font-weight: 400; word-wrap: break-word">Bartr</div>
    </div>
    <!--Scrollbar Container-->
    
    <!--Message Box Container-->
    <script>
        function goToProfile() {
            // Navigate to the login page
            window.location.href = "profile.html";
        }
    </script>
</body>
</html>