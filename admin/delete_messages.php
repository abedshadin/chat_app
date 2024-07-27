<?php include('db.php') ?>
<?php

// Starting the session, to use and
// store data in session variable
session_start();

// If the session variable is empty, this
// means the user is yet to login
// User will be sent to 'login.php' page
// to allow the user to login
if (!isset($_SESSION['admin_name'])) {
    $_SESSION['msg'] = "You have to log in first";
    header('location: login.php');
}

// Logout button will destroy the session, and
// will unset the session variables
// User will be headed to 'login.php'
// after logging out
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['admin_name']);
    header("location: login.php");
}

?>
<?php
include 'config.php'; // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['num_messages']) && is_numeric($_POST['num_messages'])) {
        $num_messages = intval($_POST['num_messages']);

        // Perform the deletion
        $stmt = $conn->prepare("
            DELETE FROM messages
            WHERE id IN (
                SELECT id
                FROM (
                    SELECT id
                    FROM messages
                    ORDER BY id ASC
                    LIMIT ?
                ) AS subquery
            )
        ");
        $stmt->bind_param("i", $num_messages); // Bind the integer parameter
        $stmt->execute();
        $stmt->close();

        // Redirect or provide feedback
        header("Location: index.php?status=deleted");
        exit();
    }
}
