<?php 
    $con = mysqli_init();
    $con->ssl_set(NULL, NULL, "ssl/cacert.pem", NULL, NULL);
    $con->real_connect('gcp.connect.psdb.cloud', 't6iojlguel9xxxqhf4vb', 
    'pscale_pw_W9alE0Qda7YzLuuyDJxL2HiOdnHVppey6OycKPTZrFY', 'codi');
    session_start();
    if(isset($_SESSION['activate'])) {
        $before = mysqli_fetch_assoc(mysqli_query($con,"select * from file where num = ".$_GET["file"]));
        if ($before['id'] == $_SESSION['activate']['id'] || $_SESSION['activate']['id'] == 'admin') {
            mysqli_query($con,"insert into trash (writer, title, exp, time)
            values ('".$before['writer']."','".urlencode($before['title'])."','".urlencode($before['exp'])."',now())");
            mysqli_query($con,"delete from file where num = ".$_GET['file']);
        }
    }
    header('Location: index.php');
    $con->close();
?>