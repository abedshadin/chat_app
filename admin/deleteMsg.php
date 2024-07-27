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
<!DOCTYPE html>
<html>
<head>
    <title>Delete Messages</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container">
    <h2 class="my-4">Delete Messages</h2>

    <form method="post" action="delete_messages.php">
        <div class="form-group">
            <label for="num_messages">Number of Messages to Delete:</label>
            <input 
                type="number" 
                id="num_messages" 
                name="num_messages" 
                class="form-control" 
                min="1" 
                step="1" 
                required
                placeholder="Enter number of messages"
            >
        </div>
        <button type="submit" class="btn btn-danger">Delete Messages</button>
    </form>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
        <div class="alert alert-success mt-3">
            Messages have been deleted.
        </div>
    <?php endif; ?>
</body>
</html>
