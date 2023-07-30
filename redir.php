<?php 
    require('lib/function.php');
    if(!empty($_POST['title']) && !empty($_POST['explain']) && 
    trim($_POST['title']) != '' && trim($_POST['explain']) != '') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $result = $awClient->putObject([
                'Bucket' => $bucketName,
                'Key' => $_FILES['file']['name'],
                'SourceFile' => $_FILES['file']['tmp_name'],
            ]);
            insertsql($result['ObjectURL']);
        } else {
            insertsql(null);
        } 
    } else {
        header('Location: index.php?mode=write&error=0');
    } 
?>