<?php
    session_start();
    // Process only POST requests
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Database configuration details
        $db_host = 'localhost';
        $db_name = 'bartr_db';
        $db_user = 'admin';
        $db_pass = 'admin';
    
        try {
            // Set up a new PDO instance for a secure database connection
            $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $db_pass);
            // Set error reporting to throw exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle connection errors by outputting a message and stopping execution
            die("Database connection failed: " . $e->getMessage());
        }
        
        // Ensure both username (or email) and password fields are provided
        if (empty($_POST['username']) || empty($_POST['password'])) {
            // Missing fields, redirect back with an error message
            header("Location: login.php?error=missing_fields");
            exit();
        }
    
        // Trim input data to remove unnecessary whitespace
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    
        // Check if either field is empty
        if (empty($username) || empty($password)) {
            header("Location: login.php?error=empty_fields");
            exit();
        }
    
        // Prepare an SQL statement to retrieve the user's details, supports login by either username or email.
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :identifier OR email = :identifier LIMIT 1");
        $stmt->execute([':identifier' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // If a user record is found, verify the password.
        if ($user) {
            // Use PHP's password_verify() to check the provided password against the stored hash.
            if (password_verify($password, $user['password'])) {
                // Login is successful, regenerate the session ID to help prevent session fixation attacks.
                session_regenerate_id(true);
    
                // Store user details in the session.
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to a protected dashboard or home page.
                header("Location: home.php");
                exit();
            } else {
                // Incorrect password: redirect with an error indicator.
                header("Location: login.php?error=invalid_credentials");
                exit();
            }
        } else {
            // No user found with the provided identifier.
            header("Location: login.php?error=invalid_credentials");
            exit();
        }
    }
?>