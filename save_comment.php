<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_system");

$user_id = $_SESSION['user_id'];
$comment = $_POST['comment'];

$stmt = $conn->prepare("INSERT INTO comments (user_id, comment) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $comment);
$stmt->execute();

header("Location: comment.php");
?>
