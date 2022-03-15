<?php
  session_start();
  include('server.php');

  if(!isset($_SESSION['username'])){
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }

  if(isset($_GET['sendpm'])){
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="veiwport" content="width=device-width, initial-scale=1"/>
	<title>Start Conversation</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
        <form action="sendPm.php" method="post">
            <?php include('errors.php'); ?>
            <div class="input-group">
              <label>Title</label>
              <input type="text" name="title" value="<?php echo $title;?>">
              <label>Recipient</label>
              <input type="text" name="recipient" value="<?php echo $recipient;?>">
              <label>Message</label>
              <input type="text" name="message" value="<?php echo $message;?>">
              <button type="submit" class="btn" name="send_message">Send</button>
            </div>
        </form>
</body>
</html>
<?php
}
?>