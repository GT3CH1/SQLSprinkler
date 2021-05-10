<?php

/*
    How to use:

    include('lib/sql.php');
    $sqlquery = new doSQL();
    $sqlquery->doSQLStuff("SELECT * FROM `Games`");
    $games = $sqlquery->get_names();
    $services = $sqlquery->get_sns();
    $directories = $sqlquery->get_dirs();
    $id = $sqlquery->get_id();

*/

class doSQL
{

    var $names = array();
    var $gpios = array();
    var $times = array();
    var $days = array();
    var $ids = array();

    private $servername;
    private $username;
    private $password;
    private $dbname;

    public function __construct()
    {
        $this->servername = apache_getenv('SQLSPRINKLER_SQL_HOST');
        $this->username = apache_getenv('SQLSPRINKLER_USER');
        $this->password = apache_getenv('SQLSPRINKLER_PASS');
        $this->dbname = apache_getenv('SQLSPRINKLER_DB');
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
        $id = array();
        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
                array_push($newnames, $row['Name']);
                array_push($newgpios, $row['GPIO']);
                array_push($newtimes, $row['Time']);
                array_push($id, $row['id']);
            }
            $this->names = $newnames;
            $this->gpios = $newgpios;
            $this->times = $newtimes;
            $this->ids = $id;
        }
    }
}

?>
