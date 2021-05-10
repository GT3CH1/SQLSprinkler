<?php
include('lib/sql.php');
$sqlquery = new doSQL();
$sqlquery->doSQLStuff("SELECT * FROM `Systems`");
$names = $sqlquery->get_names();
$gpios = $sqlquery->get_gpios();
$id = $sqlquery->get_id();
?>
<html lang="en">
<head>
    <base href="<?php echo $_SERVER['HTTP_HOST']; ?>"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SQLSprinkler</title>
    <link href="css/w3.css" type="text/css" rel="stylesheet"/>
    <link href="css/w3-flat.css" type="text/css" rel="stylesheet"/>
    <link href="css/style.css" type="text/css" rel="stylesheet"/>
    <script src="https://kit.fontawesome.com/e00a151875.js" crossorigin="anonymous"></script>
    <script src="js/jquery.js"></script>
    <script src="js/sprinkler.js"></script>
</head>
<body onload="getSprinklers();" style="display:none;">
<div>
    <!--        Begin systems, repeat this <?php echo sizeof($id); ?> times -->
    <!--        TODO: Make this out of pure html and js. -->
    <br>
    <table class="w3-table w3-table-all sprinkler-table w3-threequarter w3-display-topmiddle">
        <div id="notification" style="display: none;font-size: .75em;padding:16px;z-index: 100" class="w3-card-4 w3-white w3-display-middle">
            <span id="notification-text"></span>
            <span class="w3-button dismiss w3-red" style="float:right;"><i class="fas fa-times"></i></span>
        </div>
        <tr>
            <td>
                <div class="w3-rest" style="float:left;">
                    <p style="font-weight: bold">System Schedule</p>
                    <p>Status: <span id="schedule"></span></p>
                </div>
            </td>
            <td>
                <button id="schedule-btn"
                        class="w3-button programoff w3-round-xxlarge w3-centered mybutton sprinkler-button">Turn
                    <span id="schedule-btn-txt">Off</span>
                </button>
            </td>
        </tr>
        <?php
        for ($i = 0; $i < sizeof($id); $i++) {
            ?>
            <tr>
                <td>
                    <div class="sprinkler-info">
                        <p class="sprinkler-name">
                            <?php echo 'Zone ' . ($i + 1) ?>
                        </p>
                        <p>
                            <?php echo $names[$i]; ?>
                        </p>
                        <p>
                            Status: <span id="status-<?php echo $i; ?>">Off</p>
                    </div>
                </td>
                <td>
                    <div class="sprinkler-button">
                        <button id="<?php echo $gpios[$i]; ?>" name="toggle"
                                onclick="getData(<?php echo $i; ?>);return false;"
                                class="w3-button systemoff w3-round-xxlarge w3-center mybutton">Turn On
                        </button>
                    </div>
                </td>
            </tr>
            <?php
        }
        ?>

        <!--        End systems-->
    </table>
</div>
<div class="w3-display-bottomleft w3-center w3-flat-silver w3-dropdown-hover " style="position:fixed;">
    <a href="javascript:void(0);" id="menuopen" class="w3-button fix-bars"> <i style="z-index: 5;"
                                                                               class="fa fa-bars w3-display-middle"></i>
    </a>
    <div style="display: none;" id="menunav">
        <a id="menuclose" class="w3-button "> <i style="z-index: 5;" class="fa fa-times"></i></a>
        <a href="/" class="w3-button"><i style="z-index: 5;" class="fa fa-home"></i></a>
        <a href="settings" class="w3-button"><i style="z-index: 5;" class="fa fa-gears"></i></a>
        <a id="update" class="w3-button"><i style="z-index: 5;" class="fas fa-download"></i></a>
    </div>
</div>
</body>
</html>
