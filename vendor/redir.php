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
    direct(null,'?mode=write');
    if (isset($_FILES['file'])) {
        $result = $awClient->putObject([
            'Bucket' => $bucketName,
            'Key' => $_FILES['file']['name'],
            'SourceFile' => $_FILES['file']['tmp_name'],
        ]);
        file_put_contents('data/'.urlencode($_POST['title']),
        "\n Image Saurce: \n".$result['ObjectURL'], FILE_APPEND);;
    }
?>