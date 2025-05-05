<?php
session_start();

require "functions.php";
$user_data = checkLogin();
$currentUserId = $user_data['id'];

$chatId = $_GET['chat_id'] ?? null;
$error = '';

$pdo = new PDO("mysql:host=localhost;dbname=bartr_db;charset=utf8mb4", "admin", "admin");

// Handle new chat creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_chat_user'])) {
    $username = trim($_POST['new_chat_user']);
    $userStmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $userStmt->execute([$username]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $otherUserId = $user['id'];

        // Check if chat already exists
        $checkStmt = $pdo->prepare("SELECT id FROM chat WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)");
        $checkStmt->execute([$currentUserId, $otherUserId, $otherUserId, $currentUserId]);
        $existingChat = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingChat) {
            header("Location: messages.php?chat_id=" . $existingChat['id']);
            exit;
        } else {
            // Create new chat
            $insertStmt = $pdo->prepare("INSERT INTO chat (user1_id, user2_id) VALUES (?, ?)");
            $insertStmt->execute([$currentUserId, $otherUserId]);
            $newChatId = $pdo->lastInsertId();
            header("Location: messages.php?chat_id=" . $newChatId);
            exit;
        }
    } else {
        $error = "User not found.";
    }
}

$chatStmt = $pdo->prepare("
  SELECT c.id AS chat_id, u.id AS user_id, u.username, u.profile_pic
  FROM chat c
  JOIN users u ON u.id = IF(c.user1_id = ?, c.user2_id, c.user1_id)
  WHERE c.user1_id = ? OR c.user2_id = ?
");
$chatStmt->execute([$currentUserId, $currentUserId, $currentUserId]);
$chats = $chatStmt->fetchAll(PDO::FETCH_ASSOC);

// Send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'], $_POST['chat_id']) && !isset($_POST['new_chat_user'])) {
    $stmt = $pdo->prepare("INSERT INTO messages (chat_id, sender_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['chat_id'], $currentUserId, $_POST['message']]);
    header("Location: messages.php?chat_id=" . $_POST['chat_id']);
    exit;
}

// Load messages
$messages = [];
if ($chatId) {
    $msgStmt = $pdo->prepare("SELECT * FROM messages WHERE chat_id = ? ORDER BY timestamp ASC");
    $msgStmt->execute([$chatId]);
    $messages = $msgStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <div class="logo">Bartr</div>
        <div class="links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="addPost.php">Add Post</a>
        </div>
    </nav>

    <main>
        <div id="container">
            <div id="sidebar">
                <h2>Chats</h2>

                <!-- Start New Chat -->
                <form method="POST" class="new-chat-form" style = "display: grid;">
                    <input type="text" name="new_chat_user" placeholder="Start chat with username..." required>
                    <button type="submit" class="login_button">Start</button>
                </form>
                <?php if ($error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <!-- List of existing chats -->
                <ul>
                    <?php if (empty($chats)): ?>
                        <li>No active chats yet.</li>
                    <?php else: ?>
                        <?php foreach ($chats as $chat): ?>
                        <li>
                            <a href="?chat_id=<?= $chat['chat_id'] ?>">
                            <img width="50px" height="50px" style="object-fit:cover; border-radius:50%; overflow:hidden;" src="<?= $chat['profile_pic'] ?? 'default.png' ?>" alt="">
                            <?= htmlspecialchars($chat['username']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <div id="chat-window">
                <?php if ($chatId): ?>
                    <div id="messages">
                    <?php foreach ($messages as $msg): ?>
                        <div class="msg <?= $msg['sender_id'] == $currentUserId ? 'sent' : 'received' ?>">
                        <div><?= htmlspecialchars($msg['content']) ?></div>
                        <div class="timestamp"><?= date('M j, g:i A', strtotime($msg['timestamp'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <form method="POST">
                    <input type="hidden" name="chat_id" value="<?= $chatId ?>">
                    <input type="text" name="message" placeholder="Type a message..." required>
                    <button type="submit">Send</button>
                    </form>
                <?php else: ?>
                    <p>Select a conversation or start a new one.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>
</html>
