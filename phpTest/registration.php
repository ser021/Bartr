<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body style="background-color: #073763;">
    <h1 class="logo center" style="width: 185px; height: 80px; left: 839px; top: 40px;">Bartr</h1>
    <form action="register.php" method="POST" style="width: 300px; margin:auto; position: relative;">
        <label for="email" class="body_text">Email:</label>
        <input type="text" id="email" name="email" required><br><br>

        <label for="name" class="body_text">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="username" class="body_text">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password" class="body_text">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit" class="login_button center_button">Register</button>
    </form>
</body>
</html>