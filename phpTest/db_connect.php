<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "bartr_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!-- Login Screen -->
<form action="login.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<!-- Registration Screen -->
<form action="register.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>

<!-- Home Page -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
echo "Welcome, " . $_SESSION['username'];
?>

<!-- Profile Page -->
<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<p>Username: <?php echo $user['username']; ?></p>
<p>Email: <?php echo $user['email']; ?></p>
<img src="<?php echo $user['profile_picture'] ? 'uploads/' . $user['profile_picture'] : 'default.png'; ?>" alt="Profile Picture" width="150">
<a href="edit_profile.php">Edit Profile</a>

<!-- Edit Profile Page -->
<form action="upload.php" method="POST" enctype="multipart/form-data">
    <label>Change Profile Picture:</label>
    <input type="file" name="profile_picture" required>
    <button type="submit">Upload</button>
</form>

<!-- Messages Page -->
<?php
$sql = "SELECT * FROM messages WHERE recipient_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    echo "<p>From: " . $row['sender_id'] . " - " . $row['message'] . "</p>";
}
?>
