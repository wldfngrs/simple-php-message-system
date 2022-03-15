<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="veiwport" content="width=device-width, initial-scale=1"/>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content">
        <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
        ?>
    </div>
    <div class="header">
        <h2>Login</h2>
    </div>

    <form method="post" action="login.php">
        <?php include('errors.php');?>
        <div class="input-group">
            <label>Username</label>
  		    <input type="text" name="username" ><br>
            </br><label>Password</label>
  		    <input type="password" name="password">
            <button type="submit" class="btn" name="login_user">Log in</button>
        </div>
        <p>
            Not yet a member? <a href="register.php">Sign up</a>
        </p>
    </form>
</body>
</html>