<?php
/* Copyright 2021 Gavin Pease */
include('sql.php');
include('Zone.php');

class System
{
    /**
     * The variable that holds the reference to the sql class
     * @var doSQL
     */
    private $sqlquery;

    /**
     * System constructor.
     */
    public function __construct()
    {
        $this->sqlquery = new doSQL();
    }

    /**
     * Gets the status of all zones in the sprinkler system, and their configuratins.
     * @return string
     */
    public function getZones()
    {
        $this->sqlquery->doSQLStuff("SELECT * FROM `Systems`");
        $array = array();
        for ($i = 0; $i < sizeof($this->sqlquery->ids); $i++) {
            $gpio = $this->sqlquery->gpios[$i];
            $zonename = $this->sqlquery->names[$i];
            $runtime = $this->sqlquery->times[$i];
            $enabled = $this->sqlquery->enableds[$i] == "1";
            $autooff = boolval($this->sqlquery->autooffs[$i]);
            $currId = $this->sqlquery->ids[$i];
            $zone = new Zone($zonename, $gpio, $runtime, $enabled, $autooff, $currId);
            $array[$i] = $zone->getData();
        }
        return json_encode($array);
    }


    /**
     * Gets the status of the system schedule.
     * @return string
     */
    public function getSystemEnabled()
    {
        $enabled = $this->sqlquery->querySQL("SELECT enabled from `Enabled`");
        $isEnabled = mysqli_fetch_array($enabled)[0];
        $newJson = (object)array();
        $newJson->systemstatus = $isEnabled;
        return json_encode($newJson);
    }


    /**
     * Enables/disables the system schedule.
     */
    public function toggleSystemSchedule()
    {
        $this->sqlquery->querySQL("UPDATE Enabled set enabled= !enabled;");
        return $this->getSystemEnabled();
    }

    /**
     * Updates a zone with a matching id to the zone provided.
     * @param Zone $zone - The new zone data we are wanting replace the old with.
     * @return string
     */
    public function updateZone(Zone $zone)
    {
        $query = Zone::getUpdateQuery($zone);
        $this->sqlquery->doSQLStuff($query);
        return $query;
    }

    /**
     * Creates a new zone object given some POST data.
     * @param $postData - The data given from a POST query.
     * @return Zone
     */
    public function createZone($postData)
    {
        $gpio = $postData['gpio'];
        $id = (isset($postData['id']) ? $postData['id'] : -1);
        $name = $postData['name'];
        $runtime = $postData['runtime'];
        $enabled = $postData['scheduled'];
        $autooff = $postData['autooff'];
        return new Zone($name, $gpio, $runtime, $enabled, $autooff, $id);
    }

    /**
     * Adds a new zone to the system.
     * @param Zone $zone - The zone we are adding.
     * @return string
     */
    public function addZone(Zone $zone)
    {
        $query = Zone::getInsertQuery($zone);
        $this->sqlquery->doSQLStuff($query);
        return $query;
    }

    /**
     * Deletes the given zone.
     * @param Zone $zone - The zone to delete.
     * @return string
     */
    public function deleteZone(Zone $zone)
    {
        $query = Zone::getDeleteQuery($zone);
        $this->sqlquery->doSQLStuff($query);
        return $query;
    }


}