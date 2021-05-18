<?php
/* Copyright 2021 Gavin Pease */

/* Begin block for API calls */
include('sql.php');
include('Zone.php');
$sqlquery = new doSQL();

if (isset($_GET['systems'])) {
    $sqlquery->doSQLStuff("SELECT * FROM `Systems`");
    $gpios = $sqlquery->gpios;
    $id = $sqlquery->ids;
    $names = $sqlquery->names;
    $runtimes = $sqlquery->times;
    $enableds = $sqlquery->enableds;
    $autooffs = $sqlquery->autooffs;
    $array = array();
    for ($i = 0; $i < sizeof($id); $i++) {
        $gpio = $gpios[$i];
        $zonename = $names[$i];
        $runtime = $runtimes[$i];
        $enabled = $enableds[$i] == "1";
        $autooff = boolval($autooffs[$i]);
        $currId = $id[$i];
        $zone = new Zone($zonename,$gpio,$runtime,$enabled,$autooff,$currId);
        array_push($array, $zone->getData());
    }
    echo json_encode($array);
}

if (isset($_GET['systemstatus'])) {
    $enabled = $sqlquery->querySQL("SELECT enabled from `Enabled`");
    $isEnabled = "";
    if ($enabled) {
        while ($row = mysqli_fetch_array($enabled)) {
            $isEnabled = $row[0];
        }
        $newJson = (object)array();
        $newJson->systemstatus = $isEnabled;
        echo json_encode($newJson);
    }
}

/* Begin block for submit files */
$dir = getcwd() . "/";
if (isset($_GET['on'])) {
    $run = $_GET['on'];
    exec("sudo " . $dir . "off.py");
    exec("sudo " . $dir . "on.py " . $run . " & ");
    echo "Running... " . $run . " -> " . $dir;
}
if (isset($_GET['off'])) {
    exec("sudo " . $dir . "off.py");
    echo "Turning off. " . $_GET['off'] . " ->" . $dir;
}
if (isset ($_GET['systemenable'])) {
    $val = (($_GET['systemenable']) == "false" ? 0 : 1);
    echo $val;
    $test = $sqlquery->querySQL("UPDATE Enabled set enabled=" . $val . ";");
    var_dump($test);
}
if (isset ($_GET['update'])) {
    $test = shell_exec('/usr/bin/git fetch');
    echo $test;
    $test = shell_exec('/usr/bin/git reset');
    echo $test;
    $test = shell_exec('/usr/bin/git pull');
    echo $test;
}
if (isset($_POST['call'])) {
    $callType = $_POST['call'];
    if ($callType == "update") {
        $gpio = $_POST['gpio'];
        $id = $_POST['id'];
        $name = $_POST['name'];
        $runtime = $_POST['runtime'];
        $enabled = $_POST['scheduled'];
        $autooff = $_POST['autooff'];
        $query = "UPDATE Systems SET `Name`='" . $name . "', `GPIO`=" . $gpio . ", `Time`=" . $runtime .
            ", `Enabled`=" . $enabled . ", `Autooff`=" . $autooff . " WHERE id=" . $id;
        $sqlquery->querySQL($query);
        echo $query;
    }
    if ($callType == "add") {
        $gpio = $_POST['gpio'];
        $name = $_POST['name'];
        $runtime = $_POST['runtime'];
        $enabled = $_POST['scheduled'];
        $autooff = $_POST['autooff'];
        $query = "INSERT INTO `Systems` (`Name`, `GPIO`, `Time`, `Enabled`, `Autooff`) VALUES ('" . $name . "','" . $gpio . "','" . $runtime . "'," . $enabled . "," . $autooff . ")";
        $sqlquery->querySQL($query);
        echo $query;
    }
    if ($callType == "delete") {
        $id = $_POST['id'];
        $sqlquery->querySQL("DELETE FROM `Systems` WHERE `id` = " . $id);
    }
}
?>
