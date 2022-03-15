<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="veiwport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Registration</title>
</head>
<body>
    <div class="header">
        <h2>Sign Up</h2>
    </div>

    <form method="post" action="register.php">
        <?php include('errors.php'); ?>
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
            <label>Password</label>
            <input type="password" name="password">
            <label>Confirm password</label>
            <input type="password" name="passverif">
            <button type="submit" class="btn" name="reg_user">Sign up</button>
        </div>
        <p>
            Already a member? <a href="login.php">Sign in</a>
        </p>
    </form>
</body>
</html>