<?php
session_start();
include ('server.php');

  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }

  if(isset($_GET['changePassword'])) {
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="veiwport" content="width=device-width, initial-scale=1"/>
	<title>Change Password</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
      <form action="changePassword.php" method="post">
        <?php include('errors.php');?>
        <div class="input-group">
          <label>Old password</label>
          <input type="password" name="password">
          <label>New password</label>
          <input type="password" name="newPassword">
          <label>Confirm new password</label>
          <input type="password" name="passverif">
          <button type="submit" class="btn" name="change_password">Update password</button>
        </div>
      </form>
</body>
</html>

<?php
}
?>