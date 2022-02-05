<?php
# webpage php connect to 2 mysql DB
     header("Refresh:15");
     session_start();
     $dbhostsubacc01 = "dbip1";
     $dbhostsubacc02 = "dbip2";
     $username = "username";
     $password = "password";
     $db = "dbname";
     $hostname =  gethostname();
     $serverip = exec("ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1'");
     $dblink1 = new mysqli($dbhostsubacc01, $username, $password, $db);
     $dblink2 = new mysqli($dbhostsubacc02, $username, $password, $db);
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
      width: 100%; 
      border=0 
      cellspacing=0
      }
      .table2  {
      border-color: black; 
      border: 1px solid black;
      border-collapse: collapse;
      width: 50%; 
      text-align:center;
      font-weight:bold;
      border=0 
      cellspacing=0
      }
      .table3  {
      border-color: black; 
      border: 1px solid black;
      border-collapse: collapse;
      width: 80%; 
      text-align:center;
      font-weight:bold;
      border=0 
      cellspacing=0
      }
      .td1  {
      border-color: black; 
      border: 1px solid black;
      text-align:left;
      width: 35%;       
      font-weight:normal;
      vertical-align:top;
      border=0 
      cellspacing=0
      }
      .td2  {
      border-color: black; 
      border: 1px solid black;
      width: 35%;
      text-align:left;
      font-weight:normal;
      border=0 
      cellspacing=0
      }
      .td3  {
      border-color: black; 
      border: 1px solid black;
      width: 30%;
      text-align:left;
      font-weight:normal;
      border=0 
      cellspacing=0
      }
      .tdcolor1  {
      border-color: black; 
      border: 1px solid black;
      background-color:#F5B7B1;
      text-align:left;
      width: 20%;       
      font-weight:normal;
      vertical-align:top;
      border=0 
      cellspacing=0
      }
      .tdcolor2  {
      border-color: black; 
      border: 1px solid black;
      background-color:#EAEDED;
      text-align:left;
      width: 20%;       
      font-weight:normal;
      vertical-align:top;
      border=0 
      cellspacing=0
      }
      .tdcolor3  {
      border-color: black; 
      border: 1px solid black;
      background-color:#F9E79F;
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

<h2>Connection Status</h2>
<table class="table3">
  <tr>
    <th>Connection</th>
    <th>Status</th>
  </tr>
  <tr>
    <td class="td1">Current Server</td>
    <td class="td1">
        <?php
                echo "HOST: $hostname (IP: $serverip)";
        ?>
        </td>
  </tr>
  <tr>
    <td class="td1">Database Connection</td>
    <td class="td1">
        <?php
        if ($dblink1->connect_error) {
                print("Connection DB1: $dbhostsubacc01 failed: " . $dblink1->connect_error);
        }{
                echo "Connected DB1: $dbhostsubacc01 successfully <br>";
        }
        if ($dblink2->connect_error) {
                print("Connection DB2: $dbhostsubacc02 failed: " . $dblink2->connect_error);
        }{
                echo "Connected DB2: $dbhostsubacc02 successfully <br>";
        }
    ?>
        </td>
  </tr>

  <tr>
    <td class="td1">Insert database</td>
    <td class="td1">
        <?php
        
        $sql = "INSERT INTO web_db.logging (server, serverip) VALUES ('$hostname' , '$serverip') ";

        if ($dblink1->query($sql) === TRUE) {
          echo "New record created on DB1: $dbhostsubacc01 successfully <br>";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }

        if ($dblink2->query($sql) === TRUE) {
          echo "New record created on DB2: $dbhostsubacc02 successfully <br>";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }


        ?>
        </td>
  </tr>
</table>

<br>
<br>

<table class="table3">
	<tbody>
		<tr>
			<td colspan="2">Summaries Results</td>
		</tr>
		<tr>
			<td>
				<table class="table1">
					<tbody>
						<tr>
							<td class="table2">Server </td>
							<td class="table2">Totals</td>
						</tr>
						

            <?php
            /* Show query result1 */
            $tdcolor = "tdcolor1";
            $resultDB1 = $dblink1->query("SELECT server, COUNT(server) as count FROM web_db.logging GROUP BY server");
            while($row = $resultDB1 -> fetch_row()){  
                 echo "<tr><td class='" . $tdcolor . "'>" . $row[0] . "</td> <td class='" . $tdcolor ."'>" . $row[1] . "</td></tr>";
                 if ($tdcolor == "tdcolor1") $tdcolor = "tdcolor2";
                 elseif ($tdcolor == "tdcolor2") $tdcolor = "tdcolor1";
            }
            ?>
					</tbody>
				</table>
			</td>
			<td>
				<table class="table1">
					<tbody>
						<tr>
							<td class="table2">Server </td>
							<td class="table2">Totals</td>
						</tr>
                  
            <?php
            /* Show query result1 */
            $tdcolor = "tdcolor1";
            $resultDB2 = $dblink2->query("SELECT server, COUNT(server) as count FROM web_db.logging GROUP BY server");

            while($row = $resultDB2 -> fetch_row()){   //Creates a loop to loop through results
                 echo "<tr><td class='" . $tdcolor . "'>" . $row[0] . "</td> <td class='" . $tdcolor ."'>" . $row[1] . "</td></tr>";
                 if ($tdcolor == "tdcolor1") $tdcolor = "tdcolor2";
                 elseif ($tdcolor == "tdcolor2") $tdcolor = "tdcolor1";
            }
            ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>

<br>
<br>

<table class="table3">
	<tbody>
		<tr>
			<td colspan="2">Query from DB Last 10 Results</td>
		</tr>
		<tr>
			<td>
				<table class="table1">
					<tbody>
						<tr>
							<td class="td3">Server </td>
              <td class="td3">HOST IP</td>
							<td class="td3">Timestamp</td>
						</tr>
						

            <?php
            /* Show query result1 */
            $tdcolor = "tdcolor3";
            $resultDB1 = $dblink1->query("SELECT server, serverip, timestamp FROM web_db.logging order by timestamp DESC LIMIT 10");

            while($row = $resultDB1 -> fetch_row()){  
                 echo "<tr><td class='" . $tdcolor . "'>" . $row[0] . "</td> <td class='" . $tdcolor . "'>" . $row[1] . "</td><td class='" . $tdcolor . "'>" . $row[2] . "</td></tr>";
                 if ($tdcolor == "tdcolor3") $tdcolor = "tdcolor2";
                 elseif ($tdcolor == "tdcolor2") $tdcolor = "tdcolor3";        
            }
            ?>
					</tbody>
				</table>
			</td>
			<td>
				<table class="table1">
					<tbody>
						<tr>
							<td class="td3">Server </td>
              <td class="td3">HOST IP</td>
							<td class="td3">Last Login</td>
						</tr>
                  
            <?php
            $tdcolor = "tdcolor3";
            /* Show query result1 */
            $resultDB2 = $dblink2->query("SELECT server, serverip, timestamp FROM web_db.logging order by timestamp DESC LIMIT 10");

            while($row = $resultDB2 -> fetch_row()){   //Creates a loop to loop through results
                 echo "<tr><td class='" . $tdcolor . "'>" . $row[0] . "</td> <td class='" . $tdcolor . "'>" . $row[1] . "</td><td class='" . $tdcolor . "'>" . $row[2] . "</td></tr>";  
                 if ($tdcolor == "tdcolor3") $tdcolor = "tdcolor2";
                 elseif ($tdcolor == "tdcolor2") $tdcolor = "tdcolor3";        

            }
            ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>

<?php
    $dblink1 -> close();
    $dblink2 -> close();
?>


</body>
</html>

