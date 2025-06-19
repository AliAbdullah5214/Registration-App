<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Fetch user from database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    if ($password === $user['password']) {
        // Password matched
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: comment.php");
        exit();
    } else {
        echo "<p style='color:red;'>Invalid password.</p>";
    }
} else {
    echo "<p style='color:red;'>User not found.</p>";
}

$stmt->close();
$conn->close();
?>
