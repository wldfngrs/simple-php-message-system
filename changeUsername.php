<?php
session_start();
include ('server.php');
  
  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }

  if(isset($_GET['changeUsername'])) {
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="veiwport" content="width=device-width, initial-scale=1"/>
	<title>Change Username</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class = "edit">
      <form action="changeUsername.php" method="post">
        <?php include('errors.php');?>
        <div class="input-group">
          <label>New username</label>
          <input type="text" name="newUsername" value="<?php echo $newUsername;?>">
          <button type="submit" class="btn" name="change_username">Update Username</button>
        </div>
      </form>
    </div>
</body>
</html>
<?php
  }
?>