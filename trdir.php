<?php 
    if(isset($_POST['file'])) {
        $con = mysqli_init();
        $con->ssl_set(NULL, NULL, "ssl/cacert.pem", NULL, NULL);
        $con->real_connect('gcp.connect.psdb.cloud', 't6iojlguel9xxxqhf4vb', 
        'pscale_pw_W9alE0Qda7YzLuuyDJxL2HiOdnHVppey6OycKPTZrFY', 'codi');
        for ($i = 0;$i < count($_POST['file']);$i++) {
            mysqli_query($con,"delete from trash where num = ".$_POST['file'][$i]);
        }
        $con->close();
        header('Location: index.php?mode=trash');
    } else {
        header('Location: index.php?mode=trash&error=0');
    }
?>