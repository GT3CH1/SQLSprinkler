 <!DOCTYPE html>
 <html>
   <head>
    	<?php
       		$date = time();
	   		//get the date
     	?>
     	<meta charset="utf-8">
    	<link href="https://gavinscodetest.tk/css/style.css" rel="stylesheet" type="text/css"></link>
     	<script src="https://gavinscodetest.tk/js/1jquery.js"></script>
     	<script src="https://gavinscodetest.tk/js/sprinkler.js"></script>
    	<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
     	<title></title>
   </head>
   <body>
     <a href="edit.php" style="float: left;text-decoration: none;color: #fff;font-family: UB;font-size: 1em;">
		 	&nbspConfig
	</a>
	<br/>
    <a href="../../" style="float: left;text-decoration: none;color: #fff;font-family: UB;font-size: 1em;">
		&nbspHome
	</a>
    <center>
    	<div style="max-width: 500px;margin: 0 auto !important;float: none !important;text-align:center;">
     		<form id ="test" action="lib/submit.php" method="post">
       			<?php
       				$test1=file_get_contents('lib/sys.dat'); //get the contents of the file lib/sys.dat
       				if($test1 == 1){  // if the variable test1 is equal to one
       					echo '<p>System Schedule&nbsp&nbsp&nbspStatus: On</p>'; //echo that the system is on
       					echo '<br/><button name="sysoff" class="w3-btn w3-teal w3-xlarge w3-hover-indigo w3-round-large"> Turn Off </button><br/>'; //make a buton that says turn off
   					}else{ //else
				       echo '<p>System Schedule&nbspStatus: Off</p>'; //echo that the system is off
				       echo '<br/><button name="syson" class="w3-btn w3-blue w3-xlarge w3-round-large w3-hover-indigo"> Turn On </button><br/>'; //make a button that says turn on
       				}
        		?>
     		</form>
     		<form  action="lib/submit.php" method="post">
       			<div id="data"/>
            		<?php
						include 'lib/check.php';
					 ?>
          		</div>
     		</form>
		</center>
		<div style="bottom: 0;position:fixed;float:right;right:0;">
    		<button onclick="starts();" style="background-color:Transparent;border:0px;">
    			<img src="https://gavinscodetest.tk/img/microphone.png" width="35"/>
			</button>
  		</div>
		<div style="bottom: 0;position:fixed;float:left;left:0;">
    		<?php
				include_once('lib/version.php');  //include the php version file
				?>
  		</div>
   </body>
</html>
