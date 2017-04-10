<br>
<br><?php
	$a = '';
    $servername = "localhost"; //server ip for sql
    $username   = "root"; //server username
    $password   = "#FiddleFire"; //server password
    $dbname     = "Computers"; //database name
    $conn       = mysqli_connect($servername, $username, $password, $dbname); //connect to sql database with all parameters

    $sql    = "SELECT * FROM `list`"; // select only the username field from the table "users_table"
    $result = mysqli_query($conn, $sql); // process the query
    $username_array = array(); // start an array
    while ($row = mysqli_fetch_array($result)) // cycle through each record returned
      {
        $sysname[] = "\"" . $row['Name'] . "\""; // get the username field and add to the array above with surrounding quotes
        $sysgpio[]       = "\"" . $row['GPIO'] . "\""; // get the ip field and add to the array
        $name             = trim($username_array[$a], '"'); //trim " " from username
        $gpio               = trim($ip_array[$a], '"'); //trim " " from ip

	    exec ( "gpio read -g".$gpio."", $status );
    
	foreach ($status as $value){
		?>
			<div style="max-width: 400px;">
		<?php
  		if ($value == 1){ $data = "Off"; }else{ $data = "On"; }
   			echo '<br/><p style="float:left;" class="label"> '.$name.' <br>&nbspStatus: '.$data.'</p>';
   		if ($value == 0){
     		echo '<button style="float:right;" name="off" value='.$a.' class="w3-btn w3-xlarge w3-green w3-round-large w3-hover-aqua" id="'.$a.'" onclick="getData('.$a.')">Turn Off </button><br><br>';
   		}else{
     		echo '<button name="on" style="float:right;" value='.$a.' class="w3-btn w3-xlarge w3-blue w3-round-large w3-hover-purple" id="'.$a.'" onclick="getData('.$a.')">Turn On </button><br><br>';
   		}?>
<br>	</div>
		<?php
	       }
        }
 ?>

<script>
function getData(sys){
    var xhttp = new XMLHttpRequest();
    var test = document.getElementById(sys).name;
    
    var info=test+"="+sys;
    
    xhttp.open("GET", "/modules/sPrInkler/lib/submit.php?"+info, true);
    console.log("sending");
    console.log(info);
    xhttp.send();

}
</script>