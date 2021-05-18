<?php
// Copyright 2021 Gavin Pease

class Zone
{

    /**
     * The json value of this zone.
     * @var array
     */
    private $json;


    /**
     * Creates a new zone
     *
     * @param $name - The name of the zone.
     * @param $gpio - The gpio pin of the zone.
     * @param $runtime - How long the zone will run for (in minutes).
     * @param $enabled - Whether or not this zone is turned on for the system schedule.
     * @param $autooff - Whether or not this system automatically turns off from the web page.
     * @param $id - The unique id of this zone.
     */
    public function __construct($name, $gpio, $runtime, $enabled, $autooff, $id)
    {
        $status = boolval(exec(' which gpio && gpio -g read ' . $gpio . ' || echo 1'));
        $json = (object)array();
        $json->name = $name;
        $json->gpio = intval($gpio);
        $json->runtime = intval($runtime);
        $json->enabled = boolval($enabled);
        $json->autooff = boolval($autooff);
        $json->id = intval($id);
        $json->status = $status;
        $this->json = $json;
    }

    /**
     * Pretty-prints this system in JSON.
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->json);
    }

    /**
     * Gets the current data for this zone in an array format.
     * @return array|object
     */
    public function getData()
    {
        return $this->json;
    }

    /**
     * Returns this zone in a json format.
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Creates an SQL update statement for a zone
     * @param $name - The new name of the zone.
     * @param $gpio - The new GPIO pin of the zone.
     * @param $runtime - The new runtime of the zone.
     * @param $enabled - The new status of zone enabled
     * @param $autooff - The new status of autoff
     * @param $id - The id of the zone.
     * @return string
     */
    static function getUpdateQuery($name, $gpio, $runtime, $enabled, $autooff, $id)
    {
        $query = "UPDATE Systems SET `Name`='" . $name . "', `GPIO`=" . $gpio . ", `Time`=" . $runtime .
            ", `Enabled`=" . $enabled . ", `Autooff`=" . $autooff . " WHERE id=" . $id;
        return $query;
    }

    /**
     * @param $name - The name of the new zone.
     * @param $gpio - The gpio of the new zone.
     * @param $runtime - The run time of the new zone.
     * @param $enabled - Whether or not this current zone is enabled.
     * @param $autooff - Whether or not this current zone is automaticcaly shut off.
     * @return string
     */
    static function getInsertQuery($name,$gpio,$runtime,$enabled,$autooff){
        $query = "INSERT INTO `Systems` (`Name`, `GPIO`, `Time`, `Enabled`, `Autooff`) VALUES ('" . $name . "','" . $gpio . "','" . $runtime . "'," . $enabled . "," . $autooff . ")";
        return $query;
    }

    static function getDeleteQuery($id){
        $query = "DELETE FROM `Systems` WHERE `id` =" .$id;
        return $query;
    }
}