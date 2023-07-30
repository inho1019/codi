<?php
    require('lib/function.php');
    global $con;
    $duname = mysqli_fetch_assoc(mysqli_query($con,"select name from users where name = '".$_POST['nickname']."'"));
    $duid = mysqli_fetch_assoc(mysqli_query($con,"select id from users where id = '".$_POST['id']."'"));
    if (!isset($duname) && !isset($duid)) {
        $register = mysqli_query($con,'insert into users (name,id,password,date)
        values ("'.$_POST['nickname'].'","'.$_POST['id'].'","'.$_POST['password'].'",NOW())');
        if($register === true) {
            echo '<script>
                alert("Register Success");
                location.href = "index.php";
            </script>';
        } else {
            header('Location: index.php?mode=register&error=0');
        }
    } else {
        header('Location: index.php?mode=register&error=1');
    };
?>