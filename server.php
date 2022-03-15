<?php
session_start();

// initializing variables
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'user', 'database_password', 'database_name');

// REGISTER USER
if (isset($_POST['reg_user'])) {
    // receive all input values from the form
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $passverif = mysqli_real_escape_string($db, $_POST['passverif']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
    if ($password != $passverif) {
	    array_push($errors, "The two passwords do not match");
    }
    if(strlen($password) < 6){
        array_push($errors, "Password must contain at least six characters");
    }
    
    // first check the database to make sure 
    // a user does not already exist with the same username
    $user_check_query = "SELECT * FROM users WHERE username='$username' LIMIT 1;";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
        if ($user['username'] === $username) {
          array_push($errors, "Username already exists");
        }
    }

    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password);//encrypt the password before saving in the database
        
        // count the number of users to give ID to new user
        $dn = mysqli_num_rows(mysqli_query($db, 'select id from users'));
        $id = $dn+1;
        $query = "INSERT INTO users (id, username, password) 
              VALUES('$id','$username', '$password');";
        mysqli_query($db, $query);

        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
    }   
}

// verify captcha
if(isset($_POST['login_user'])){
    // generate random captcha string
    $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';

    function generate_string($input, $strength = 5, $secure = true){
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++){
            $random_character = $input[random_int(0, $input_length -1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    // generate background image
    $image = imagecreatetruecolor(200, 50);

    imageantialias($image, true);

    $colors = [];

    $red = rand(125, 175);
    $green = rand(125, 175);
    $blue = rand(125, 175);

    for ($i = 0; $i < 5; $i++){
        $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
    }

    imagefill($image, 0, 0, $colors[0]);

    for ($i = 0; $i < 10; $i++){
        imagesetthickness($image, rand(2, 10));
        $line_color = $colors[rand(1,4)];
        imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40,60), $line_color);
    }

    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);
    $textcolors = [$black, $white];

    // dirname(__FILE__) specifies the full directory of the current running script
    $fonts = [dirname(__FILE__).'\fonts\GideonRoman-Regular.ttf', dirname(__FILE__).'\fonts\Shizuru-Regular.ttf', dirname(__FILE__).'\fonts\RobotoSerif-Italic-VariableFont_GRAD,opsz,wdth,wght.ttf', dirname(__FILE__).'\fonts\RobotoSerif-VariableFont_GRAD,opsz,wdth,wght.ttf'];

    $string_length = 6;
    $captcha_string = generate_string($permitted_chars, $string_length);

    $_SESSION['captcha_text'] = $captcha_string;

    for ($i = 0; $i < $string_length; $i++){
        $letter_space = 170/$string_length;
        $initial = 15;
        //imagettftext() used to write text to the image using TrueType fonts.
        imagettftext($image, 24, rand(-15, 15), $initial + $i*$letter_space, rand(25,45), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);
    }

    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image);
}

// LOG IN USER
if(isset($_POST['login_user'])){
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password';";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
          $_SESSION['username'] = $username;
          $_SESSION['success'] = "You are now logged in";
          header('location: index.php');
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

// CHANGE USERNAME
if(isset($_POST['change_username'])){
    $newUsername = mysqli_real_escape_string($db, $_POST['newUsername']);

    if(empty($newUsername)) {
        array_push($errors, "Username is required");
    }

    // check database to make sure newUsername isn't already
    // taken by another user
    $user_check_query = "SELECT * FROM users WHERE username='$newUsername' LIMIT 1;";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result); //mysqli_fetch_assoc returns a result row as an associative array

    if($user) { // if user exists
        if($user['username'] === $newUsername){
            array_push($errors, "Username already exists");
        }
    }

    // update username if there are no errors in the form
    if(count($errors) == 0){
        $query = 'update users set username="'.$newUsername.'" where username="'.$_SESSION['username'].'";';
        mysqli_query($db, $query);

        $_SESSION['username'] = $newUsername;
        $_SESSION['success'] = "You have successfuly changed your username";
        header('location: index.php');
    }
    else{
        header("location:changeUsername.php?changeUsername='1'");
    }
}

// CHANGE PASSWORD
if(isset($_POST['change_password'])){
    $oldPassword = mysqli_real_escape_string($db, $_POST['password']);
    $newPassword = mysqli_real_escape_string($db, $_POST['newPassword']);
    $passverif = mysqli_real_escape_string($db, $_POST['passverif']);

    if(empty($newPassword)){
        array_push($errors, "Old password is required");
    }
    if(empty($oldPassword)){
        array_push($errors, "New password is required");
    }
    if ($newPassword != $passverif) {
	    array_push($errors, "Password confirmation does not match");
    }
    if(strlen($newPassword) < 6) {
        array_push($errors, "Password must contain at leat six characters");
    }

    // update password if there are no errors on the form
    if(count($errors) == 0){
        $oldPassword = md5($oldPassword);
        $query = 'select * from users where username="'.$_SESSION['username'].'" and password="'.$oldPassword.'";';
        $results = mysqli_query($db, $query);
        if(mysqli_num_rows($results) == 1){
            $newPassword = md5($newPassword);
            $query1 = "UPDATE users SET password='$newPassword' WHERE password='$oldPassword';";
            mysqli_query($db, $query1);

            $_SESSION['success'] = "You have successfully changed your password";
            header('location: index.php');
        }else{
            array_push($errors, "Wrong password inputted");
        }
    }
    else{
        header("location: changePassword.php?changePassword='1'");
    }
}

// START CONVERSATION
if(isset($_POST['send_message'])){
    $title = mysqli_real_escape_string($db, $_POST['title']);
    $recipient = mysqli_real_escape_string($db, $_POST['recipient']);
    $message = mysqli_real_escape_string($db, $_POST['message']);

    // check if recipient exists
    $user_check_query = "SELECT * FROM users WHERE username='$recipient' LIMIT 1;";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    if(!$user){
        array_push($errors, "Recipient does not exist");
    }

    if(empty($title)){
        $title = "null";
    }
    if(empty($recipient)){
        array_push($errors, "Recipient field is required!");
    }
    if(empty($message)){
        array_push($errors, "You haven't included a message!");
    }
    if($recipient == $_SESSION['username']){
        array_push($errors, "You cannot send a message to yourself");
    }

    // send message if there are no errors in the form
    if(count($errors)==0){
        // get id of sender (id)
        $id_check_query='SELECT * FROM users WHERE username="'.$_SESSION['username'].'";';
        $idresult = mysqli_query($db, $id_check_query);
        $idr = mysqli_fetch_assoc($idresult);
        $id = $idr['id'];

        // get id of recipient(id2)
        $id2_check_query="SELECT * FROM users WHERE username='$recipient';";
        $id2result = mysqli_query($db, $id2_check_query);
        $id2r = mysqli_fetch_assoc($id2result);
        $id2 = $id2r['id'];

        $message_send_query = 'INSERT INTO pm(id, id2, title, user1, user2, message, timestamp, user1read, user2read) 
                                VALUES("'.$id.'","'.$id2.'","'.$title.'","'.$_SESSION['username'].'","'.$recipient.'","'.$message.'","'.time().'","yes", "no");';
        
        mysqli_query($db, $message_send_query);

        $_SESSION['success'] = "Message sent to $recipient";
        header("location: profile.php?veiwProfile='1'");
    }
    else{
        header("location:sendPm.php?sendpm='1'");
    }
}

// REPLY MESSAGE
if(isset($_POST['send'])){
    $title = 'null';
    $reply = mysqli_real_escape_string($db, $_POST['reply']);
    $recipient = $_SESSION['recipient'];
    $sender = $_SESSION['sender'];
    
    if(empty($reply)){
        array_push($errors, "You did not include a reply!");
    }

    if (count($errors)===0){
        // get id of sender(id)
        $id_check_query="SELECT * FROM users WHERE username='$sender';";
        $idresult= mysqli_query($db, $id_check_query);
        $idr = mysqli_fetch_assoc($idresult);
        $id = $idr['id'];

        // get id of recipient
        $id2_check_query="SELECT * FROM users WHERE username='$recipient';";
        $id2result = mysqli_query($db, $id2_check_query);
        $id2r = mysqli_fetch_assoc($id2result);
        $id2 = $id2r['id'];

        $reply_send_query = 'INSERT INTO pm(id, id2, title, user1, user2, message, timestamp, user1read, user2read) 
                            VALUES("'.$id.'","'.$id2.'","'.$title.'","'.$sender.'","'.$recipient.'","'.$reply.'","'.time().'","yes", "no");';
        
        mysqli_query($db, $reply_send_query);

        $_SESSION['success'] = "Reply sent to $recipient";
        unset($_SESSION['sender']);
        unset($_SESSION['recipient']);
        header("location: readPm.php?readpm='1'");
    }
    else{
        header("location:readPm.php?readpm='1'");
    }
}

// DELETE ACCOUNT
if(isset($_POST['delete'])){
    $password = mysqli_real_escape_string($db, $_POST['password']);
    if(empty($password)){
        array_push($errors, "Password field is empty");
    }

    if(count($errors)===0){
        $delete_query1 = 'DELETE FROM users where username="'.$_SESSION['username'].'";';
        // you could figure out how to reassign the id column?
        $delete_query2 = 'DELETE FROM pm where user2="'.$_SESSION['username'].'";';
        // same here
        mysqli_query($db, $delete_query1);
        mysqli_query($db, $delete_query2);
        $_SESSION['success'] = "Account deleted";
        unset($_SESSION['username']);
        header("location:login.php");
    }
    else{
        header("location:accountDelete.php?delete='1'");
    }
}
?>