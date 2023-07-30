<?php 
require('lib/function.php');
global $con;
    if(isset($_POST['file'])) {
        for ($i = 0;$i < count($_POST['file']);$i++) {
            mysqli_query($con,"delete from trash where num = ".$_POST['file'][$i]);
        }
        header('Location: index.php?mode=trash');
    } else {
        header('Location: index.php?mode=trash&error=0');
    }
?>