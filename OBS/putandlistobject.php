<?php
header("Refresh:15");
include '/var/www/html/obs/credentials.php';
session_start();
// Import the dependency library.
require 'vendor/autoload.php';
// Import the SDK code library during source code installation.
// require 'obs-autoloader.php';
// Declare the namespace.
use Obs\ObsClient;
// Create an instance of ObsClient.
$obsClient = new ObsClient ( [ 
       'key' => $ak,
       'secret' => $sk,
       'endpoint' => $endpoint
] );

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

$mydate = new DateTime();
$mydate->setTimezone(new \DateTimeZone('+0700'));
$mylist = $mydate->format('Y-m-d');

$myranStr = generateRandomString(100);
$myfile = $mydate->format('Y-m-d H-i-s') .".txt";

$PutResp = $obsClient->putObject([
       'Bucket' => $bucketName,
       'Key' => $myfile,
       'Body' => $myranStr
]);


$resp = $obsClient->listObjects ( [ 
       'Bucket' => $bucketName,
       // Set that 100 objects whose names follow test in lexicographical order will be listed.
       'MaxKeys' => 1000,
       'Marker' => $mylist
] );



printf("Put Object RequestId:%s\n",$PutResp['RequestId']);

?>


<!DOCTYPE html>
<html>

<head>

     <style type="text/css">
      table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      }
      .table1  {
      border-color: black; 
      border: 1px solid black;
      border-collapse: collapse;
      text-align:center;
      font-weight:bold;
      width: 80%; 
      border=0 
      cellspacing=0
      }
      .td1  {
      border-color: black; 
      border: 1px solid black;
      text-align:Center;
      width: 30%;       
      font-weight:bold;
      vertical-align:top;
      border=0 
      cellspacing=0
      }
      .tdsub1  {
      border-color: black; 
      border: 1px solid black;
      text-align:left;
      width: 20%;
      background-color:#ffccc9;
      font-weight:normal;
      vertical-align:top;
      border=0 
      cellspacing=0
      }
      .tdsub2  {
      border-color: black; 
      border: 1px solid black;
      background-color:#ecf4ff;
      text-align:left;
      width: 20%;       
      font-weight:normal;
      vertical-align:top;
      border=0 
      cellspacing=0
      }


     </style>
</head>
<body>

<h2>Today OBS Files Result!!</h2>
<table class="table1">
	<tbody>
		<tr>
			<td colspan="3">Summaries Results</td>
		</tr>
    <tr>
    
	    <td class='td1'>No</td>    
	    <td class='td1'>File Name</td>
	    <td class='td1'>Last Modified</td>
    </tr>
    
<?php   
#printf ( "RequestId:%s\n", $resp ['RequestId'] );
$tdclass = "tdsub1";
$n = 1;
foreach ( $resp ['Contents'] as $index => $content ) {
       echo "<tr>";
       #printf ( "Contents[%d]\n", $index + 1 );
       #printf ( "Key:%s\n", $content ['Key'] );
       #printf ( "LastModified:%s\n", $content ['LastModified'] );
       #printf ( "ETag:%s\n", $content ['ETag'] );
       #printf ( "Size:%s\n", $content ['Size'] );
       #printf ( "Owner[ID]:%s\n", $content ['Owner'] ['ID'] );
       #printf ( "StorageClass:%s\n", $content ['StorageClass'] );
			 echo "<td class='" . $tdclass . "'>" . $n . "</td>";
			 echo "<td class='" . $tdclass . "'>" . $content ['Key'] . "</td>";
			 echo "<td class='" . $tdclass . "'>" . $content ['LastModified'] . "</td>";
       echo "</tr>";
       if ($tdclass == "tdsub1") $tdclass = "tdsub2";
       elseif ($tdclass == "tdsub2") $tdclass = "tdsub1";
       $n++;
}

?>

   </tbody>
</table>

</body>
</html>
