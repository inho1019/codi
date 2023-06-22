<?php 
    rename('data/'.$_GET['file'],'trash/'.$_GET['file']);
    header('Location: index.php');
?>