<?php
require "functions.php";
session_start();

$user_data = checkLogin();
$user_id = $user_data['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);

    // Use your existing function
    $success = deletePost($post_id, $user_id);

    if ($success) {
        header("Location: home.php?message=Post+Deleted");
        exit();
    } else {
        echo "Error: You are not authorized to delete this post or post does not exist.";
    }
} else {
    echo "Invalid request.";
}