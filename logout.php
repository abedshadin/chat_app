<?php
session_start();
include 'config.php';

// Update user status to offline
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("UPDATE users SET online_status = 'offline' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

session_destroy();
header("Location: login.php");
exit();
?>
