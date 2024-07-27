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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<title>Admin Panel</title>
</title>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<script src="../js/jquery-3.4.1.slim.min.js"></script>
<script src="../bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<link rel="stylesheet" href="../style.css">
<body>

<div class="header">
        <h2>Cloud</h2>
    </div>
    <div class="content">
  
    
    <?php if (isset($_SESSION['success'])) : ?>
            <div class="error success" >
                <h3>
                    <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <p>
                Hello
                <strong>
                    <?php echo$_SESSION['admin_name'];?>
                </strong>, Welcome to our site.
            </p>
            <?php
$search_value=$_SESSION["admin_name"];






?>





<center>
<a class="nav-link" href="index.php">Dashboard <span class="sr-only"></span></a> &nbsp;&nbsp;&nbsp;
<a class="nav-link" href="users.php">Users</a>&nbsp;&nbsp;&nbsp;

</center>


<center>

<div class="">

   <?php

include('db.php');
    $s= mysqli_query($con,"select * from users order by id  ");
    ?>
    <table border=1>
        <tr>
            <th class="text-center" width="8%">Username</th>

         
              <th class="text-center">Status</th>
            <th class="text-center">Active</th>
            <th class="text-center">Block</th>
        </tr>



        <?php
        while($r = mysqli_fetch_array($s)){
            ?>

            <tr>
            <td class="text-center h6">
                <?php echo $r['username']; ?>
           
    <td class="h6 pl-1">
<?php echo $r['u_status']; ?>
</td>
      <td align="center">
       <a class=" btn btn-danger" href="active.php?i=<?php echo $r['id']; ?>">✔ </a>
            </td>
                <td align="center">       <a class=" btn btn-danger" href="block.php?i=<?php echo $r['id']; ?>">🚫 </a>

            </tr>
              <?php
        }
        ?>
        </table>
        <center>
      
      <br>
                <a href="index.php?logout='1'" style="color: red;">
                    Logout
                </a>
            </center>
       </div>

   </center>
      </body>
      </html>
