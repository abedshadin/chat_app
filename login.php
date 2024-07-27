<?php
include 'config.php';
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query to get user details
    $stmt = $conn->prepare("SELECT id, password, u_status FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id, $hashed_password, $status);
        $stmt->fetch();

        // Check if user status is active and passwords match
        if ($status === 'Active' && password_verify($password, $hashed_password)) {
            // Update user's online status
            $stmt->close(); // Close previous statement before preparing a new one
            $stmt = $conn->prepare("UPDATE users SET online_status = 'online' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // Set session and redirect
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid credentials or inactive account.";
        }

        $stmt->close(); // Close the statement
    } else {
        // Handle query preparation error
        echo "Database query failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body class="container">
    <h2 class="my-4">Login</h2>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <input type="submit" value="Login" class="btn btn-primary">
    </form>
    <div><a href="register.php" class="btn btn-primary mt-3">Register</a></div>
</body>
</html>
