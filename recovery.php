<style>
    * {
        background-color:black;
        color:white;
    }
</style>
<a href="index.php">MAIN</a><br><br>
<?php
session_start();
$con = mysqli_init();
$con->ssl_set(NULL, NULL, "ssl/cacert.pem", NULL, NULL);
$con->real_connect('gcp.connect.psdb.cloud', 't6iojlguel9xxxqhf4vb', 
    'pscale_pw_W9alE0Qda7YzLuuyDJxL2HiOdnHVppey6OycKPTZrFY', 'codi');
$data = mysqli_fetch_assoc(mysqli_query($con,"select * from trash where num = ".$_GET['file']));
if ($data['writer'] == $_SESSION['activate']['name'] || $_SESSION['activate']['id'] == 'admin') { 
    echo "title : ".urldecode($data['writer'])."<br><br>";
    echo "explain : ".str_replace("___",htmlspecialchars_decode("&nbsp;"),nl2br(htmlspecialchars(urldecode($data['exp']))));
} else {
    echo "title : ".$data['writer']."<br><br>";
    echo "explain : ".$data['exp'];
}
$con->close();
?>
