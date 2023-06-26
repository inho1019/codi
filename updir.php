<?php 
    session_start();
    require('lib/function.php');
    direct(rename('data/'.$_POST['file'],'data/'.urlencode($_POST['title'])),'?mode=update&file='.urlencode($_POST['file']),null);
?>