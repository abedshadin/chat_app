<?php

// Starting the session, necessary
// for using session variables
session_start();

// Declaring and hoisting the variables
$admin_name = "";
$email    = "";
$errors = array();
$_SESSION['success'] = "";

// DBMS connection code -> hostname,
// username, password, database name
$db = mysqli_connect('localhost', 'root', '', 'chat_app');

// Registration code
if (isset($_POST['reg_admin'])) {

    // Receiving the values entered and storing
    // in the variables
    // Data sanitization is done to prevent
    // SQL injections
    $admin_name = mysqli_real_escape_string($db, $_POST['admin_name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // Ensuring that the user has not left any input field blank
    // error messages will be displayed for every blank input
    if (empty($admin_name)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }

    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
        // Checking if the passwords match
    }

    // If the form is error free, then register the user
    if (count($errors) == 0) {

        // Password encryption to increase data security
        $password = md5($password_1);

        // Inserting data into table
        $query = "INSERT INTO admin (admin_name, email, password, Status)
                  VALUES('$admin_name', '$email','$password', 'Block')";

        mysqli_query($db, $query);

        // Storing username of the logged in user,
        // in the session variable
        $_SESSION['admin_name'] = $admin_name;

        // Welcome message
        $_SESSION['success'] = "Need Admin Permission To Active Account";

        // Page on which the user will be
        // redirected after logging in
        header('location:index.php?logout="1"');
    }
}

// User login
if (isset($_POST['login_admin'])) {

    // Data sanitization to prevent SQL injection
    $admin_name = mysqli_real_escape_string($db, $_POST['admin_name']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Error message if the input field is left blank
    if (empty($admin_name)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    // Checking for the errors
    if (count($errors) == 0) {

        // Password matching
        $password = md5($password);

        $query = "SELECT * FROM admin WHERE admin_name=
                '$admin_name' AND password='$password' AND Status='Active'";
        $results = mysqli_query($db, $query);

        // $results = 1 means that one user with the
        // entered username exists
        if (mysqli_num_rows($results) == 1) {

            // Storing username in session variable
            $_SESSION['admin_name'] = $admin_name;

            // Welcome message
            $_SESSION['success'] = "You have logged in!";

            // Page on which the user is sent
            // to after logging in
            header('location: index.php');
        }
        else {

            // If the username and password doesn't match
            array_push($errors, "Wrong Input or Need Admin Permission");
        }
    }
}

?>
