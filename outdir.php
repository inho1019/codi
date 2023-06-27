<?php
    session_start();
    $con = mysqli_init();
    $con->ssl_set(NULL, NULL, "ssl/cacert.pem", NULL, NULL);
    $con->real_connect('gcp.connect.psdb.cloud', 't6iojlguel9xxxqhf4vb', 
    'pscale_pw_W9alE0Qda7YzLuuyDJxL2HiOdnHVppey6OycKPTZrFY', 'codi');
    unset($_SESSION['activate']);
    echo '<script>
                alert("Logout Success");
                location.href = "index.php";
            </script>';
    $con->close();
?>