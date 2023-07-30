<style>
    * {
        background-color:black;
        color:white;
    }
</style>
<a href="index.php">MAIN</a><br><br>
<?php
require('lib/function.php');
global $con;
$data = mysqli_fetch_assoc(mysqli_query($con,"select * from trash where num = ".$_GET['file']));
if ($data['writer'] == $_SESSION['activate']['name'] || $_SESSION['activate']['id'] == 'admin') { 
    echo "title : ".urldecode($data['writer'])."<br><br>";
    echo "explain : ".str_replace("___",htmlspecialchars_decode("&nbsp;"),nl2br(htmlspecialchars(urldecode($data['exp']))));
} else {
    echo "title : ".$data['writer']."<br><br>";
    echo "explain : ".$data['exp'];
}
?>
