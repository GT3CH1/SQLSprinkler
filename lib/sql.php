<?php
/* Copyright 2021 Gavin Pease*/
class doSQL
{

    var $names = array();
    var $gpios = array();
    var $times = array();
    var $days = array();
    var $ids = array();
    var $enableds = array();

    private $servername;
    private $username;
    private $password;
    private $dbname;

    public function __construct()
    {
        require __DIR__ . '/../vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable('../');
        $dotenv->load();
        $this->servername = $_SERVER['SQLSPRINKLER_SQL_HOST'];
        $this->username = $_SERVER['SQLSPRINKLER_USER'];
        $this->password = $_SERVER['SQLSPRINKLER_PASS'];
        $this->dbname = $_SERVER['SQLSPRINKLER_DB'];
    }

    function querySQL($query)
    {
        $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);
        $query = mysqli_query($conn, $query);
        if ($query)
            return $query;
        return mysqli_error($conn);
    }

    function doSQLStuff($query)
    {
        $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);
        $result = mysqli_query($conn, $query);
        $newnames = array();
        $newgpios = array();
        $newtimes = array();
        $newenabled = array();
        $id = array();
        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
                array_push($newnames, $row['Name']);
                array_push($newgpios, $row['GPIO']);
                array_push($newtimes, $row['Time']);
                array_push($id, $row['id']);
                array_push($newenabled,$row['Enabled']);
            }
            $this->names = $newnames;
            $this->gpios = $newgpios;
            $this->times = $newtimes;
            $this->ids = $id;
            $this->enableds = $newenabled;
        }
    }
}

?>
