<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="veiwport" content="width=device-width, initial-scale=1"/>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Verify Captcha</h2>
    </div>

    <form method="post" action="captcha.php">
        <?php include('errors.php');?>
        <div class="input-group">
            <img src="captcha.php" alt="CAPTCHA" class="captcha-image">
            <br>
            <p><label>Please enter the Captcha text</label>
            <input type="text" name="captcha_challenge" pattern="[A-Z]{6}">
            <button type="submit">Verify</button>
        </div>
    </form>
</body>
</html>