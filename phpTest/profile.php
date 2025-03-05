<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body style="background-color: #073763;">
    <!--Header Container-->
    <div style="width: 100%; height: 91px;">
        <div style="width: 100%; height: 90px; left: 0px; top: 1px; position: absolute; border-bottom: 1px #FFE599 solid"></div>
        <!--Messages Button-->
        <button class="login_button" onclick="goToMessages()" style="right: 37px; top:19px; position: absolute;">Messages</button>
        <!--Logo-->
        <div style="width: 185px; height: 73px; left: 37px; top: 0px; position: absolute; color: #FFE599; font-size: 80px; font-family: Castoro; font-weight: 400; word-wrap: break-word">Bartr</div>
    </div>
    <!--Profile Container, for now its just checking that its connected correctly by showing username-->
    <h1 class = "logo"> Username: <?php echo $user['username']; ?></h1>
    <script>
        function goToMessages() {
            // Navigate to Registration
            window.location.href = "messages.php"
        }
    </script>
</body>
</html>