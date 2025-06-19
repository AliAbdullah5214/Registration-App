<?php
$conn = new mysqli("localhost", "root", "", "user_system");

$result = $conn->query("SELECT username, comment, created_at FROM comments ORDER BY created_at DESC");

while ($row = $result->fetch_assoc()) {
    echo '<div class="message">';
    echo '<strong>' . htmlspecialchars($row['username']) . '</strong>';
    echo htmlspecialchars($row['comment']) . '<br>';
    echo '<small style="color:gray;">' . date("M d, Y h:i A", strtotime($row['created_at'])) . '</small>';
    echo '</div>';
}
?>
