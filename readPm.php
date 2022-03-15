<?php
session_start();
include('server.php');

  if(!isset($_SESSION['username'])){
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }

  if(isset($_GET['readpm'])){
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="veiwport" content="width=device-width, initial-scale=1"/>
	<title>Conversations</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="reply">
<?php
        $result = mysqli_query($db, 'Select * from pm where user2="'.$_SESSION['username'].'" order by timestamp desc;');
        while($row = mysqli_fetch_array($result)){
            $_SESSION['sender'] = $_SESSION['username']; // current user is stored in sender session
            $_SESSION['recipient'] = $row['user1']; // user to be replied to is stored in recipient session
            mysqli_query($db, 'UPDATE pm SET user2read="yes" WHERE user2="'.$_SESSION['$username'].'";');
?>
            <form action="readPm.php" method="post">
            <label>
<?php           
                $result2 = mysqli_query($db, 'Select * from pm where user2="'.$_SESSION['username'].'" and user1="'.$row['user1'].'"');
                while($row2 = mysqli_fetch_array($result2)){
                    echo $row2["user1"].": ".$row2["message"];?></br><?php
                }
?>
            </label>
                <?php include('errors.php'); ?>
                <input type="text" name="reply" value="<?php echo $reply;?>">
                <button type="submit" class="btn" name="send">Send</button>
            </form>
    </div>
    <div class="content">
<?php
        echo $_SESSION['success']; 
        unset($_SESSION['success']);
        }
?>
    </div>
</body>
</html>
<?php
}
?>