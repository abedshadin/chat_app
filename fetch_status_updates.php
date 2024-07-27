<?php
include 'config.php';

header('Content-Type: application/json');

// Fetch online users
$query = "SELECT username FROM users WHERE online_status = 'online'";
$result = $conn->query($query);

$onlineUsers = [];
while ($row = $result->fetch_assoc()) {
    $onlineUsers[] = $row;
}

echo json_encode($onlineUsers);
?>
