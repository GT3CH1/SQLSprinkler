<?php
	exec ("cat data/sys1.dat", $data1);
	exec ("cat data/sys2.dat", $data1);
	exec ("cat data/sys3.dat", $data1);
	exec ("cat data/sys4.dat", $data1);
	exec ("cat data/sys5.dat", $data1);
	exec ("cat data/sys6.dat", $data1);
	exec ("cat data/sys7.dat", $data1);
	exec ("cat data/sys8.dat", $data1);
	exec ("cat data/sys9.dat", $data1);
	exec ("cat data/sys10.dat", $data1);
	$a = '';
?>
<style>
	div#c{
		margin-left: 10px;
	}
	#test{
		float: left;
		margin-right: 10px;
		color :#fff;
		font-size: .75em;
	}
	select#time{
  		border: 0px solid;
  		font-size: .75em;
        float:right;
	}
</style>
<br/>
<?php
	foreach ($data1 as $value){
  		echo '<div id="test">';
		$test = $value / 60;
		if($a=="10"){
  			echo"System 10";
		}else{
			echo "System ".++$a."&nbsp&nbsp";
		}
		echo '</div>';
		echo "<select id='time' name=".$a.">";
		if ($value == "0"){
			echo "<option selected value='0'>Off</option>";
		}else{
  			echo "<option selected value='$value'>".$test." Minutes</option>";
		}
		echo "<option disabled></option>";
		if($value =="300"){
		  	echo "<option value='0'>Off</option>";
		  	echo "<option value='600'>10 Minutes</option>";
		  	echo "<option value='900'>15 Minutes</option>";
		  	echo "<option value='1200'>20 Minutes</option>";
		}elseif ($value =="600") {
  			echo "<option value='0'>Off</option>";
			echo "<option value='300'>5 Minutes</option>";
			echo "<option value='900'>15 Minutes</option>";
			echo "<option value='1200'>20 Minutes</option>";
		}elseif($value =="900"){
			echo "<option value='0'>Off</option>";
			echo "<option value='300'>5 Minutes</option>";
			echo "<option value='600'>10 Minutes</option>";
			echo "<option value='1200'>20 Minutes</option>";
		}elseif($value =="1200"){
  			echo "<option value='0'>Off</option>";
  			echo "<option value='300'>5 Minutes</option>";
  			echo "<option value='600'>10 Minutes</option>";
  			echo "<option value='900'>15 Minutes</option>";
		}elseif ($value =="0") {
			echo "<option value='300'>5 Minutes</option>";
			echo "<option value='600'>10 Minutes</option>";
			echo "<option value='900'>15 Minutes</option>";
			echo "<option value='1200'>20 Minutes</option>";
		}
		echo "</br>";
		echo "</br>";
		echo "</select>";
	}
 ?>
