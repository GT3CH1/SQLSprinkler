<?php
/* Copyright 2021 Gavin Pease */

/* Begin block for API calls */
include('sql.php');
$sqlquery = new doSQL();

if (isset($_GET['systems'])) {
    $sqlquery->doSQLStuff("SELECT * FROM `Systems`");
    $gpios = $sqlquery->gpios;
    $id = $sqlquery->ids;
    $names = $sqlquery->names;
    $runtimes = $sqlquery->times;
    $enableds = $sqlquery->enableds;
    $array = array();
    for ($i = 0; $i < sizeof($id); $i++) {
        $value = exec(' which gpio && gpio -g read ' . $gpios[$i] . ' || echo 1');
        $array[$i] = (object)array();
        $array[$i]->gpio = $gpios[$i];
        $array[$i]->status = ($value == 0 ? "on" : "off");
        $array[$i]->zonename = $names[$i];
        $array[$i]->runtime = $runtimes[$i];
        $array[$i]->enabled = $enableds[$i] == "1";
        $array[$i]->id = $id[$i];
    }
    $json = json_encode($array);
    echo $json;
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
        $query = "UPDATE Systems SET `Name`='" . $name . "', `GPIO`=" . $gpio . ", `Time`=" . $runtime . ", `Enabled`=" . $enabled . " WHERE id=" . $id;
        $sqlquery->querySQL($query);
        echo $query;
    }
    if ($callType == "add") {
        $gpio = $_POST['gpio'];
        $name = $_POST['name'];
        $runtime = $_POST['runtime'];
        $enabled = $_POST['scheduled'];
        $sqlquery->querySQL("INSERT INTO `Systems` (`Name`, `GPIO`, `Time`) VALUES ('" . $name . "','" . $gpio . "','" . $runtime . "','" . $enabled . "')");
    }
    if ($callType == "delete") {
        $id = $_POST['id'];
        $sqlquery->querySQL("DELETE FROM `Systems` WHERE `id` = " . $id);
    }
}
?>
