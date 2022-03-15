<?php
include('server.php');
$result = mysqli_query($db, 'Select * from pm where user2="'.$_SESSION['username'].'" order by timestamp desc;');
$array = mysqli_fetch_array($result);
foreach($array as $row ){
    echo $row;?></br><?php
}
?>