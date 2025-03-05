<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            header("Location: home.php"); // Redirect to homepage
            exit();
        } else {
            echo "Invalid credentials!";
        }
    } else {
        echo "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body style="background-color: #073763; justify-content: center;">
    <!--Header-->
    <div class="center" style="width: 185px; height: 80px; color: #FFE599; font-size: 80px; font-family: Castoro; font-weight: 400; word-wrap: break-word">Bartr</div>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 405px; height: 405px" src="https://placehold.co/405x405" />
    <div style="align-self: stretch; height: 254px; position: relative">
            
    <!-- Login Form -->
    <form action="login.php" method="POST" style="width: 300px; margin:auto; position: relative;">
        <label for="username" class="body_text">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password" class="body_text">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit" class="login_button center_button">Log In</button>
        </form>
    </div>
            <script>
                function goToHome() {
                    // Navigate to the login page
                    window.location.href = "home.php";
                }
            </script>
</body>
</html>

