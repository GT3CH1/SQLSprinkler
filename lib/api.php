<?php
/* Copyright 2021 Gavin Pease */

/* Begin block for API calls */
include('System.php');

$system = new System();

if (isset($_GET['systems']))
    echo $system->getZones();

if (isset($_GET['systemstatus']))
    echo $system->getSystemEnabled();


/* Begin block for post queries */
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
if (isset ($_POST['systemtoggle']))
    return $system->toggleSystemSchedule();

if (isset ($_GET['update'])) {
    $test = shell_exec('/usr/bin/git fetch');
    echo $test;
    $test = shell_exec('/usr/bin/git reset');
    echo $test;
    $test = shell_exec('/usr/bin/git pull');
    echo $test;
}

if (isset($_POST['call'])) {
    $query = "";
    $myZone = $system->createZone($_POST);
    switch ($_POST['call']) {
        case "update":
            $query = $system->updateZone($myZone);
            break;
        case "add":
            $query = $system->addZone($myZone);
            break;
        case "delete":
            $query = $system->deleteZone($myZone);
            break;
        default:
            break;
    }
    echo $query;
}

