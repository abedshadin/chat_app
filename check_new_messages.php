<?php
include 'config.php';
session_start();

// Initialize response array
$response = ['new_messages_count' => 0];

try {
    $lastCheckTime = isset($_GET['lastCheckTime']) ? $_GET['lastCheckTime'] : date('Y-m-d H:i:s', 0);

    // Prepare the query to count new messages
    $stmt = $conn->prepare("SELECT COUNT(*) AS new_messages_count FROM messages WHERE created_at > ?");
    $stmt->bind_param("s", $lastCheckTime);
    $stmt->execute();
    $stmt->bind_result($new_messages_count);
    $stmt->fetch();
    $stmt->close();

    // Update the response array with the count of new messages
    $response['new_messages_count'] = $new_messages_count;
} catch (Exception $e) {
    // Log the error and handle it
    error_log($e->getMessage());
    $response['error'] = 'An error occurred while checking for new messages.';
}

// Set content type to JSON and output the response
header('Content-Type: application/json');
echo json_encode($response);
?>
