// update_typing_status.php
<?php
include 'config.php';
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['typing_status'])) {
    $userId = $_SESSION['user_id'];
    $typingStatus = (int) $_POST['typing_status'];

    $stmt = $conn->prepare("UPDATE users SET typing_status = ? WHERE id = ?");
    $stmt->bind_param("ii", $typingStatus, $userId);
    $stmt->execute();
}
?>
