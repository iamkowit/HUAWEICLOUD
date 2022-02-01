<?php
include 'credentials.php';

?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<h2>OBS LIST OBJECTS</h2>


<?php
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
    require '../vendor/autoload.php'; // sample env
}

if (file_exists('obs-autoloader.php')) {
    require 'obs-autoloader.php';
} else {
    require '../obs-autoloader.php'; // sample env
}

use Obs\ObsClient;
use Obs\ObsException;
use function GuzzleHttp\json_encode;


$obsClient = ObsClient::factory(array (
    'key' => $ak,
    'secret' => $sk,
    'endpoint' => $endpoint,
));

echo ObsClient::AclAuthenticatedRead;

$obsClient->initLog(array (
        'FilePath' => './logs',
        'FileName' => 'eSDK-OBS-PHP.log',
        'MaxFiles' => 10,
        'Level' => WARN
));


function ListObjects() {
    global $obsClient;
    global $bucketName;
    echo "list objects start...\n";
    try {
        $resp = $obsClient->listObjects(array (
                'Bucket' => $bucketName,
                'Delimiter' => '',
                'Marker' => '',
                'MaxKeys' => '',
                'Prefix' => ''
        ));
        printf("HttpStatusCode:%s\n", $resp ['HttpStatusCode']);
        printf("RequestId:%s\n", $resp ['RequestId']);
        printf("IsTruncated:%d,Marker:%s,NextMarker:%s,Name:%s\n", $resp ['IsTruncated'], $resp ['Marker'], $resp ['NextMarker'], $resp ['Name']);
        printf("Prefix:%s,Delimiter:%s,MaxKeys:%d\n", $resp ['Prefix'], $resp ['Delimiter'], $resp ['MaxKeys']);
        $i = 0;

        foreach ( $resp ['CommonPrefixes'] as $CommonPrefixe ) {
            printf("CommonPrefixes[$i][Prefix]:%s\n", $CommonPrefixe ['Prefix']);
            $i ++;
        }
        $i = 0;
        foreach ( $resp ['Contents'] as $content ) {
            printf("Contents[$i][ETag]:%s,Contents[$i][Size]:%d,Contents[$i][StorageClass]:%s\n", $content ['ETag'], $content ['Size'], $content ['StorageC
lass']);
            printf("Contents[$i][Key]:%s,Contents[$i][LastModified]:%s\n", $content ['Key'], $content ['LastModified']);
            printf("Contents[$i][Owner][ID]:%s\n", $content ['Owner'] ['ID']);
            $i ++;
        }
    } catch ( ObsException $e ) {
        echo $e;
    }
}


  
function UploadPart() {
    global $obsClient;
    global $bucketName;
    global $objectKey;
    $myranStr = generateRandomString(100);
    $tmpfile = tmpfile();
    $tmpfile_path = stream_get_meta_data($tmpfile)['uri'];
    fwrite($tmpfile, $myranStr);

    echo "upload part start...\n";
    try {
        $resp = $obsClient->uploadPart(array (
                'Bucket' => $bucketName,
                'Key' => $objectKey,
                'UploadId' => 'uploadid',
                'PartNumber' => 1,
                // 'Body' => 'test',
                'SourceFile' => $tmpfile_path
        ));
        printf("HttpStatusCode:%s\n", $resp ['HttpStatusCode']);
        printf("RequestId:%s\n", $resp ['RequestId']);
        printf("ETag:%s\n", $resp ['ETag']);
    } catch ( ObsException $e ) {
        echo $e;
    }
}


  function PutObject() {
    global $obsClient;
    global $bucketName;
    global $objectKey;

    /* Generate text */
    $myranStr = generateRandomString(100);
    // echo "my Random String " .$myranStr ;

    $mydate = new DateTime();
    $mydate->setTimezone(new \DateTimeZone('+0700'));
    $myfilename = $mydate->format('YmdHis');
    /* Generate temp file  */
    $tmpfile = tmpfile();
    $tmpfile_path = stream_get_meta_data($tmpfile)['uri'];
    fwrite($tmpfile, $myranStr);
    // $tmpfile_content = file_get_contents($tmpfile_path);
    echo $tmpfile ;
    // echo $tmpfile_content ;


    echo "put object start...\n";
    try {
       $resp = $obsClient->putObject(array (
               'Bucket' => $bucketName,
               'Key' => $myfilename,
               'Metadata' => array (
                       'test' => "value"
               ),
               // 'Body'=>'msg to put',
               'ContentType' => 'text/plain',
               'SourceFile' => $tmpfile_path
       ));
       printf("HttpStatusCode:%s\n", $resp ['HttpStatusCode']);
       printf("RequestId:%s\n", $resp ['RequestId']);
       printf("ETag:%s,VersionId:%s\n", $resp ['ETag'], $resp ['VersionId']);
    } catch ( ObsException $e ) {
        echo $e;
    }
}

  function generateRandomString($length = 25) {

    $mydate = new DateTime();
    $mydate->setTimezone(new \DateTimeZone('+0700'));
    $mytimestamp = $mydate->format('Y-m-d H-i-s');

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = "Generate time " . $mytimestamp . " \n";

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
<table style="width:50%"> <tr>  <td> list of Objects ....
<?php


ListObjects();

?> </td> </tr> </table> <?php

PutObject();

?>


</body>
</html>
