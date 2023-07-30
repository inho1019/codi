<?php
    require('lib/function.php');
    global $con;
    $account = mysqli_fetch_assoc(mysqli_query($con,"select password from users where id = '".$_POST['id']."'"));
    if(isset($account)) {
        if ($account['password'] == $_POST['password']) {
            $_SESSION['activate'] = mysqli_fetch_assoc(mysqli_query($con,"select name,id from users where id = '".$_POST['id']."'"));   
            echo '<script>
                alert("Login Success");
                location.href = "index.php";
            </script>';
        }
        else {
            header('Location: index.php?mode=login&error=0');
        }
    } else {
        header('Location: index.php?mode=login&error=0');
    }
?>