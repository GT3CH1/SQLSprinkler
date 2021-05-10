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
class doSQL{

    var $names = array();
    var $gpios = array();
    var $times = array();
	var $days = array();
    var $id = array();
    
    function set_id($d){
        $this->id = $d;
    }
    function set_names($a){
        $this->names = $a;
    }
    function set_gpios($e){
        $this->gpios = $e;
    }
    function set_times($b){
        $this->times = $b;
    }
	function set_days($c){
		$this->days = $c;
	}

    function get_id(){
        return $this->id;
    }
    function get_names(){
        return $this->names;
    }
    function get_gpios(){
        return $this->gpios;
    }
    function get_times(){
        return $this->times;
    }
	function get_days(){
		return $this->days;
	}

    function querySQL($query){
        $servername = "pimation";
        $username = "web";
        $password = "X4vJOQVCF8WnnoJq";
        $dbname = "SQLSprinkler";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $query = mysqli_query($conn, $query);
        if($query)
            return $query;
        return mysqli_error($conn);
    }
    function doSQLStuff($query){
        $a = 0;
        $servername = "192.168.1.143";
        $username = "web";
        $password = "X4vJOQVCF8WnnoJq";
        $dbname = "SQLSprinkler";
        $conn   = mysqli_connect($servername, $username, $password, $dbname);
        $result = mysqli_query($conn, $query);
        $newnames = array();
        $newgpios = array();
        $newtimes = array();
		$newdays = array();
        $id = array();
        if($result){
            while($row = mysqli_fetch_array($result)){
                array_push($newnames, $row['Name']);
                array_push($newgpios, $row['GPIO']);
                array_push($newtimes, $row['Time']);
				array_push($newdays, $row['Days']);
                array_push($id, $row['id']);
            }

            $this->set_names($newnames);
            $this->set_gpios($newgpios);
            $this->set_times($newtimes);
			$this->set_days($newdays);
            $this->set_id($id);
        }
    }
}
 ?>
