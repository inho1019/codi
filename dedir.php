<?php 
    require('lib/function.php');
    global $con;
    if(isset($_SESSION['activate'])) {
        $before = mysqli_fetch_assoc(mysqli_query($con,"select * from file where num = ".$_GET["file"]));
        if ($before['id'] == $_SESSION['activate']['id'] || $_SESSION['activate']['id'] == 'admin') {
            mysqli_query($con,"insert into trash (writer, title, exp, time)
            values ('".$before['writer']."','".urlencode($before['title'])."','".urlencode($before['exp'])."',now())");
            mysqli_query($con,"delete from file where num = ".$_GET['file']);
        }
    }
    header('Location: index.php');
?>