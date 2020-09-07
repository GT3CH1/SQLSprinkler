<?php
include( 'lib/sql.php' );
$sqlquery = new doSQL();
$sqlquery->doSQLStuff( "SELECT * FROM `Systems`" );
$names = $sqlquery->get_names();
$gpios = $sqlquery->get_gpios();
$id = $sqlquery->get_id();
?>
<!doctype html>
<html>
<head>
<base href="<?php echo $_SERVER['HTTP_HOST'].$url; ?>"/>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SQLSprinkler</title>
<link href="css/w3.css" type="text/css" rel="stylesheet" />
<link href="css/w3-flat.css" type="text/css" rel="stylesheet" />
<link href="css/style.css" type="text/css" rel="stylesheet" />
<script src="https://kit.fontawesome.com/e00a151875.js" crossorigin="anonymous"></script> 
<script src="js/jquery.js"></script> 
<script src="js/sprinkler.js"></script>
</head>
<body onload="getSprinklers();" style="display:hidden;"> 
<div>
    <div class="w3-display-topmiddle w3-threequarter w3-padding-small striped">
        <div class="w3-rest">
            <div style="float:left;">
                <p>System Schedule<br>
                    Status: <span id="schedule"></span></p>
            </div>
            <div style="float:right;margin-top:8px">
                <button id="schedule-btn" onclick="systemToggle();return false;" class="w3-button programoff w3-round-xxlarge w3-centered" >Turn <span id="schedule-btn-txt">Off</span></button>
            </div>
        </div>
        <hr>
        <br>
        <br>
        <!--        Begin systems, repeat this <?php echo sizeof($id); ?> times --> 
        <!--        TODO: make an API system to get the status of each gpio. Cron?-->
        <?php
        for ( $i = 0; $i < sizeof( $id ); $i++ ) {
            ?>
        <div class="w3-rest">
            <div style="float:left;" >
                <p>
                    <?php echo 'Zone '.($i+1).' - '.$names[$i]; ?>
                    <br>
                    Status: <span id="status-<?php echo $i; ?>">Off</p>
            </div>
            <div style="float:right;margin-top:8px;">
                <button id="<?php echo $gpios[$i]; ?>" name="toggle" onclick="getData(<?php  echo $i; ?>);return false;" class="w3-button systemoff w3-round-xxlarge w3-center" >Turn On</button>
            </div>
        </div>
        <hr>
        <br>
        <?php
        }
        ?>
        
        <!--        End systems-->
    </div>
    <div class="w3-display-bottomleft w3-center w3-flat-silver w3-dropdown-hover " style="position:fixed;">
        <a href="javascript:void(0);" id="menuopen" class="w3-button fix-bars"> <i style="z-index: 5;" class="fa fa-bars w3-display-middle"></i> </a>
        <div style="display: none;" id="menunav" >
			<a id="menuclose" class="w3-button "> <i style="z-index: 5;" class="fa fa-times"></i></a> 
			<a href="/" class="w3-button"><i style="z-index: 5;" class="fa fa-home"></i></a> 
			<a href="settings" class="w3-button"><i style="z-index: 5;" class="fa fa-gears"></i></a>
			<a id="update" class="w3-button"><i style="z-index: 5;" class="fas fa-download"></i></a>
        </div>
    </div>
</div>
</body>
</html>
