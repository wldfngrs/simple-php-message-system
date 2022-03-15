<?php
session_start();
include ('server.php');
  
  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }

  if(isset($_GET['delete'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="veiwport" content="width=device-width, initial-scale=1"/>
    <title>Delete account</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="error success">
        <h2>
            <?php 
                echo $_SESSION['success']; 
          	    unset($_SESSION['success']);
            ?>
        </h2>
    </div>
    <form action="accountDelete.php" method="post">
        <?php include('errors.php') ?>
        <div class="input-group">
            <label>Enter password to confirm action</label>
            <input type="password" name="password">
            <button type="submit" class="btn" name="delete">Confirm delete</button>
        </div>
    </form>
</body>
</html>
<?php
}
?>