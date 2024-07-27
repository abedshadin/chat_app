<?php
include 'config.php';

header('Content-Type: application/json');

// Fetch users with typing status = 1
$query = "SELECT username FROM users WHERE typing_status = 1";
$result = $conn->query($query);

$typingUsers = [];
while ($row = $result->fetch_assoc()) {
    $typingUsers[] = $row['username'];
}

// Debugging output
error_log('Typing Users: ' . json_encode($typingUsers));

echo json_encode($typingUsers);
?>
