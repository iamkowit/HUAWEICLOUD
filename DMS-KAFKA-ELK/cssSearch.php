<?php
## Search message from Cloud Search Service and display

header("Refresh:15");

$css_host = "** css server ip **";
$css_user = "** username **";
$css_pass = "** password **";
$url = "https://$css_user:$css_pass@$css_host/myindex/_search/";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "Content-Type: application/json",
);

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = '{
       "from" : 0, 
       "size" : 30,
       "sort": [ {
            "timestamp": {
                     "order": "desc"       
                     }    
            }
       ]
       }';

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

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
      width: 20%;       
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
      background-color:#67fd9a;
      font-weight:normal;
      vertical-align:top;
      border=0 
      cellspacing=0
      }
      .tdsub2  {
      border-color: black; 
      border: 1px solid black;
      background-color:#fcff2f;
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
<h2 style='color:black'> Producer message to DMS-Kafka: </h1>

<?php

exec(' ** command run Producer message to DMS-Kafka ** ', $output, $return);

// Return will return non-zero upon an error
if (!$return) {
    echo "<h2> <p style='color:green';> Successfully </p> </h2>";
} else {
    echo "<h2> <p style='color:red';> Failed </p> </h2>";
}

?>

<h2>30 Last Consumer DMS-Kafka and sent to Cloud Search Service Result!!</h2>

<table class="table1">
	<tbody>
		<tr>
			<td colspan="5">Summaries Results</td>
		</tr>
		<tr>
			<td class="td1">Hostname</td>
			<td class="td1">Hostip</td>
			<td class="td1">Timestamp</td>
			<td class="td1">Message</td>
			<td class="td1">Topic</td>
		</tr>



<?php

$resp = curl_exec($curl);
curl_close($curl);
$result = json_decode(json_encode($resp),true);
$hits= json_decode($result, true); 
$hits = $hits['hits']['hits'];
foreach($hits as $hitsIndex => $hitsValue){
		echo "<tr> ";    
    $tdclass = "tdsub1";    
    if ($hitsValue['_source']['hostname'] == "ecs-subacc02" ){
       $tdclass = "tdsub2";
    }
        echo "<td class='" . $tdclass . "'>" . $hitsValue['_source']['hostname'] . " </td>" ;
        echo "<td class='" . $tdclass . "'>" . $hitsValue['_source']['hostip'] . " </td>" ;    
        echo "<td class='" . $tdclass . "'>" . $hitsValue['_source']['timestamp'] . " </td>" ;
        echo "<td class='" . $tdclass . "'>" . $hitsValue['_source']['message'] . " </td>" ;
        echo "<td class='" . $tdclass . "'>" . $hitsValue['_source']['topic'] . " </td>" ;			
		echo "</tr>";

}
?>
   </tdoby>
</table>


<?php

?>

</body>
</html>

