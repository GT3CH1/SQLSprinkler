<?php
// Copyright 2021 Gavin Pease
include('../lib/sql.php');
$sqlquery = new doSQL();
$sqlquery->doSQLStuff("SELECT * FROM `Systems`");
$id = $sqlquery->ids;
$names = $sqlquery->names;
$times = $sqlquery->times;
$gpios = $sqlquery->gpios;
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SQLSprinkler</title>
    <link href="../css/w3.css" type="text/css" rel="stylesheet"/>
    <link href="../css/w3-flat.css" type="text/css" rel="stylesheet"/>
    <link href="../css/style.css" type="text/css" rel="stylesheet"/>
    <script src="https://kit.fontawesome.com/e00a151875.js" crossorigin="anonymous"></script>
    <script src="../js/jquery.js"></script>
    <script src="../js/sprinkler.js"></script>
    <script src="../js/settings.js"></script>
</head>
<body>
<div class="w3-display-topmiddle w3-threequarter w3-mobile w3-padding-small">
    <table class="w3-table-all w3-centered sprinkler-table" id="table">
        <tr>
            <th>Zone</th>
            <th>Name</th>
            <th>Run Time</th>
            <th>Actions</th>
        </tr>
        <!-- Change this to pure html. -->
        <?php
        for ($i = 0;
        $i < sizeof($id);
        $i++) {
        ?>
        <tr>
            <td><?php echo $i + 1; ?></td>
            <td><?php echo $names[$i]; ?></td>
            <td><?php echo $times[$i]; ?></td>
            <td>
                <button id="<?php echo $i; ?>" value="edit" class="w3-button w3-gray w3-round-large">Edit</button>
                &nbsp;<button id="<?php echo $i; ?>" class="w3-button w3-red w3-round-large" value="delete">Delete
                </button>
            </td>
            <?php
            }
            ?>
    </table>
</div>
<div class="w3-display-bottomleft w3-center w3-flat-silver w3-dropdown-hover " style="position:fixed;">
    <a href="javascript:void(0);" id="menuopen" class="w3-button fix-bars"> <i style="z-index: 5;"
                                                                               class="fa fa-bars w3-display-middle"></i></a>
    <div style="display: none;" id="menunav">
        <a id="menuclose" class="w3-button"><i style="z-index: 5;" class="fa fa-times"></i></a>
        <a href="../" class="w3-button"><i style="z-index: 5;" class="fa fa-home"></i></a>
        <a id="add" class="w3-button"><i style="z-index: 5;" class="fas fa-plus"></i></a>
    </div>
</div>
<div id="edit" style="display:none;"></div>
</body>
</html>
