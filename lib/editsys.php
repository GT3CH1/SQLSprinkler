	<link href="https://gavinscodetest.tk/css/normalize.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link href="https://gavinscodetest.tk/css/style.css" rel="stylesheet" type="text/css"></link>
    <div style="position:fixed;top:0;left:0;">
       <a href="../edit.php" class="w3-btn w3-hover-lime w3-black" style="float: left;">Config</a>
       <a href="../../.." class="w3-btn w3-hover-pink w3-black" style="float: left">Home</a>
   </div>
    	<div id="texts" style="text-align: center;padding:5px;font-size: 2em;color:white;">
            <center>
<form action="editsys-submit.php" method="get">
    <div style="width: 400px;font-size: 1em">
    
<input name="old_name" type="hidden"  style="font-size: .5em;float:right;margin-top: 10px;color: black;" value="<?php echo $_GET['system'] ?>"></input>    
        <br>
        <span style="float:left;">New Name:</span> <input name="station" type="text" style="font-size: .5em;float:right;margin-top: 10px;color: black;" value="<?php echo $_GET['system']; ?>"></input>
        <br>
        <span style="float:left;">GPIO Pin:</span> <input name="gpio" type="number" value="<?php echo $_GET['gpio']; ?>" min="1" max="40" style="font-size: .5em;float:right;margin-top: 10px;color: black;"></input>
        <br>
        <span style="float:left;">Time(Minutes)</span> <input name="time" type="number" value="<?php echo $_GET['time']; ?>"min="1" max="120" style="font-size: .5em;float:right;margin-top: 10px;color: black;"></input>
        <br>
        <input type="submit" value="Submit" style="color:black;" class="w3-btn w3-blue"></input>
    </div>
    
</form>