<?php
include 'config.php';
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['status'])) {
    $userId = $_SESSION['user_id'];
    $status = $_POST['status'];

    // Ensure valid status
    if ($status === 'online' || $status === 'offline') {
        $stmt = $conn->prepare("UPDATE users SET online_status = ?, last_activity = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $userId);
        $stmt->execute();
    }
}
?>
