<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "user_system");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $comment = trim($_POST['comment']);

    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (user_id, username, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $username, $comment);
        $stmt->execute();
    }
}

// Fetch all comments
$result = $conn->query("SELECT username, comment, created_at FROM comments ORDER BY created_at DESC");
$comments = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chat Room</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
    }

    .chat-container {
      max-width: 600px;
      margin: 30px auto;
      background: white;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      height: 90vh;
    }

    .chat-header {
      background: #007bff;
      color: white;
      padding: 15px;
      text-align: center;
      font-size: 20px;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .chat-body {
      flex-grow: 1;
      overflow-y: auto;
      padding: 20px;
      display: flex;
      flex-direction: column-reverse;
    }

    .message {
      background: #e4e6eb;
      padding: 10px 15px;
      margin-bottom: 12px;
      border-radius: 8px;
      max-width: 80%;
    }

    .message strong {
      color: #007bff;
      display: block;
      margin-bottom: 5px;
    }

    .chat-footer {
      padding: 15px;
      background: #f8f9fa;
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }

    .chat-footer form {
      display: flex;
      gap: 10px;
    }

    .chat-footer input[type="text"] {
      flex: 1;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .chat-footer button {
      background: #007bff;
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
    }

    .chat-footer button:hover {
      background: #0056b3;
    }

  </style>
</head>
<body>

<div class="chat-container">
  <div class="chat-header">
    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! 💬 Chat Room
  </div>

  <div class="chat-body" id="chat-body">
  <!-- Comments will be loaded here dynamically by JavaScript -->
</div>


  <div class="chat-footer">
    <form method="POST">
      <input type="text" name="comment" placeholder="Type your message..." required>
      <button type="submit">Send</button>
    </form>
  </div>
</div>
<script>
function loadComments() {
  fetch("load_comments.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("chat-body").innerHTML = data;
    });
}

// Load every 3 seconds
setInterval(loadComments, 3000);
window.onload = loadComments;
</script>

</body>
</html>
