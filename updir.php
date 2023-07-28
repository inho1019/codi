<?php 
    require 'vendor/autoload.php';

    use Aws\S3\S3Client;
    use Aws\S3\Exception\S3Exception;

    $bucketName = 'codingdiary';
    $accessKeyId = 'AKIAXPHWSBHLWATBGOMJ';
    $secretAccessKey = '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';
    $region = 'eu-west-2';

    $awClient = new S3Client([
        'version' => 'latest',
        'region' => $region,
        'credentials' => [
            'key' => $accessKeyId,
            'secret' => $secretAccessKey,
        ],
    ]);
    session_start();
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