<?php
session_start();
include ('server.php');
  
  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }

  if(isset($_GET['veiwProfile'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="veiwport" content="width=device-width, initial-scale=1"/>
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
      <h2><?php echo $_SESSION['username'];?> profile</h2>
    </div>
    <div class="content">
        <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
        <p> <a href="readPm.php?readpm='1'" style="color: blue;">Veiw conversation</p>
        <p> <a href="sendPm.php?sendpm='1'" style="color: blue;">Start new conversation</p>
        <p> <a href="changeUsername.php?changeUsername='1'" style="color: blue;">change username</a></p>
        <p> <a href="changePassword.php?changePassword='1'" style="color: blue;">change password</a></p>
        <p> <a href="index.php" style="color:blue;">Home page</p>
        <p> <a href="accountDelete.php?delete='1'" style="color:red;">Delete account</p>
    </div>
</body>
</html>
<?php
}
?>