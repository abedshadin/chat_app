<?php
include 'config.php';
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['status'])) {
    $user_id = $_SESSION['user_id'];
    $status = $_POST['status'];

    // Validate status value
    if ($status !== 'online' && $status !== 'offline') {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid status']);
        exit();
    }

    // Update the user's status
    $stmt = $conn->prepare("UPDATE users SET online_status = ?, last_activity = NOW() WHERE id = ?");
    $stmt->bind_param("si", $status, $user_id);
    $stmt->execute();
    $stmt->close();
}
?>
