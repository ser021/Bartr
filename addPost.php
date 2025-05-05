<?php
require 'functions.php';
session_start();
$user_data = checkLogin();
$currentUserId = $user_data['id'];

$errors = [];
$success = '';
$pdo = new PDO("mysql:host=localhost;dbname=bartr_db;charset=utf8mb4", "admin", "admin");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $imagePaths = [];

    if (!$title || !$description) {
        $errors[] = "Title and description are required.";
    }

    // Handle uploaded images
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$index]);
            $targetPath = "listingimages/" . time() . "_" . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $imagePaths[] = $targetPath;
            } else {
                $errors[] = "Failed to upload image: $fileName";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO listings (owner_id, title, description, images) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $currentUserId,
            $title,
            $description,
            implode(',', $imagePaths)
        ]);

        $success = "Post created successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body style="display:grid; place-items:center;">
    <nav>
        <div class="logo">Bartr</div>
        <div class="links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="messages.php">Messages</a>
        </div>
    </nav>

    <?php if (!empty($errors)): ?>
    <div class="error">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <div class="logo">Add Post</div>
    <br><br>
    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" rows="6" required></textarea>

        <label>Images:</label>
        <input type="file" name="images[]" accept="image/*" multiple>

        <button type="submit">Post</button>
    </form>
</body>
</html>
