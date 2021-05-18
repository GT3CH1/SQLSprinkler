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
        $zone = new Zone($zonename, $gpio, $runtime, $enabled, $autooff, $currId);
        $array[$i] = $zone->getData();
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
if (isset($_POST['state'])) {
    switch ($_POST['state']) {
        case "on":
            $run = $_POST['gpio'];
            exec("sudo " . $dir . "off.py");
            exec("sudo " . $dir . "on.py " . $run . " & ");
            echo "Running... " . $run . " -> " . $dir;
            break;
        default:
            exec("sudo " . $dir . "off.py");
            echo "Turning off. ";
            break;
    }

}
if (isset ($_GET['systemenable'])) {
    $val = (($_GET['systemenable']) == "false" ? 0 : 1);
    echo $val;
    $test = $sqlquery->querySQL("UPDATE Enabled set enabled=" . $val . ";");
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
    $query = "";
    $gpio = $_POST['gpio'];
    $id = (isset($_POST['id']) ? $_POST['id'] : -1);
    $name = $_POST['name'];
    $runtime = $_POST['runtime'];
    $enabled = $_POST['scheduled'];
    $autooff = $_POST['autooff'];
    switch ($callType) {
        case "update":
            $query = Zone::getUpdateQuery($name, $gpio, $runtime, $enabled, $autooff, $id);
            $sqlquery->querySQL($query);
            break;
        case "add":
            $query = Zone::getInsertQuery($name, $gpio, $runtime, $enabled, $autooff);
            $sqlquery->querySQL($query);
            break;
        case "delete":
            $query = Zone::getDeleteQuery($id);
            $sqlquery->querySQL($query);
            break;
        default:
            break;
    }
    echo $query;
}

