<?php
    session_start();

    // Process only POST requests
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check that all required fields are provided
        if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirmpassword'])) {
            header("Location: register.php?error=missing_fields");
            exit();
        }

        // Trim input data to remove unwanted whitespace
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirmpassword']);

        // Validate that none of the fields are empty
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            header("Location: register.php?error=empty_fields");
            exit();
        }

        // Check that password and confirmation match
        if ($password !== $confirm_password) {
            header("Location: register.php?error=password_mismatch");
            exit();
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: register.php?error=invalid_email");
            exit();
        }

        // Validate username (allowing only alphanumeric characters and underscores, between 3 and 50 characters)
        if (!preg_match("/^[a-zA-Z0-9_]{3,50}$/", $username)) {
            header("Location: register.php?error=invalid_username");
            exit();
        }

        // Database configuration details – adjust these values to match your setup
        $db_host = 'localhost';
        $db_name = 'bartr_db';
        $db_user = 'admin';
        $db_pass = 'admin';

        try {
            // Create a new PDO instance for a secure connection
            $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        // Check if the username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email
        ]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existingUser) {
            header("Location: register.php?error=user_exists");
            exit();
        }

        // Hash the password using PHP's built-in function
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $result = $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password_hash
        ]);

        // If the insert was successful, optionally log in the user immediately
        if ($result) {
            // Retrieve the newly inserted user ID
            $user_id = $pdo->lastInsertId();

            // Regenerate session ID for security reasons (preventing session fixation)
            session_regenerate_id(true);

            // Store user details in session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            // Redirect the user to a welcome or dashboard page
            header("Location: home.php?message=registration_success");
            exit();
        } else {
            // If registration fails, redirect with an error message
            header("Location: register.php?error=registration_failed");
            exit();
        }
    }
?>