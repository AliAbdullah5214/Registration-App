<?php
$conn = new mysqli("localhost", "root", "", "user_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize & receive POST data
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);  // plain text

$dob = $_POST['dob'];
$gender = $_POST['gender'];

// Check if user already exists by username or email
$check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$check->bind_param("ss", $username, $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<p style='color:red;'>User already registered with this username or email.</p>";
    echo "<a href='registration.html'>Go back</a>";
} else {
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, dob, gender) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $password, $dob, $gender);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Registration successful!</p>";
        echo "<a href='login.html'>Click here to Login</a>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$check->close();
$conn->close();
?>
