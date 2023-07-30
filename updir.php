<?php 
    require('lib/function.php');
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $result = $awClient->putObject([
            'Bucket' => $bucketName,
            'Key' => $_FILES['file']['name'],
            'SourceFile' => $_FILES['file']['tmp_name'],
        ]);
        updatesql($result['ObjectURL'],$_POST['file']);
    } else {
        updatesql(null,$_POST['file']);
    }
?>