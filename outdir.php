<?php
    require('lib/function.php');
    global $con;
    unset($_SESSION['activate']);
    echo '<script>
                alert("Logout Success");
                location.href = "index.php";
            </script>';
?>