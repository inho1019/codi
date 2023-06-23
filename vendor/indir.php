<?php
    $con = mysqli_init();
    $con->ssl_set(NULL, NULL, "ssl/cacert.pem", NULL, NULL);
    $con->real_connect('gcp.connect.psdb.cloud', 't6iojlguel9xxxqhf4vb', 
    'pscale_pw_W9alE0Qda7YzLuuyDJxL2HiOdnHVppey6OycKPTZrFY', 'codi');
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
    $con->close();
?>