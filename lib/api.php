<?php
/* Begin block for API calls */
include('sql.php');
$sqlquery = new doSQL();

if (isset($_GET['systems'])) {
    $sqlquery->doSQLStuff("SELECT * FROM `Systems`");
    $gpios = $sqlquery->get_gpios();
    $id = $sqlquery->get_id();
    $names = $sqlquery->get_names();
    $runtimes = $sqlquery->get_times();
    $array = array();
    for ($i = 0; $i < sizeof($id); $i++) {
        $value = shell_exec('gpio -g read ' . $gpios[$i] . ' || exit 0');
        $array[$i] = (object)array();
        $array[$i]->gpio = $gpios[$i];
        $array[$i]->status = ($value == 0 ? "on" : "off");
        $array[$i]->zonename = $names[$i];
        $array[$i]->runtime = $runtimes[$i];
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
        $zone = $_POST['zone'];
        $name = $_POST['name'];
        $runtime = $_POST['runtime'];
        $query = "UPDATE Systems SET `Name`='" . $name . "', `GPIO`=" . $gpio . ", `Time`=" . $runtime . " WHERE id=" . $zone;
        $sqlquery->querySQL($query);
        echo $query;
    }
    if ($callType == "add") {
        $gpio = $_POST['gpio'];
        $name = $_POST['name'];
        $runtime = $_POST['runtime'];
        $sqlquery->querySQL("INSERT INTO `Systems` (`Name`, `GPIO`, `Time`) VALUES ('" . $name . "','" . $gpio . "','" . $runtime . "')");
    }
    if ($callType == "delete") {
        $zone = $_POST['zone'];
        $sqlquery->querySQL("DELETE FROM `Systems` WHERE `id` = " . $zone);
    }
}
?>
