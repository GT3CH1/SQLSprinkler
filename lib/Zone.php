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
        $status = !boolval(exec(' which gpio && gpio -g read ' . $gpio . ' || echo 1'));
        $json = (object)array();
        $json->name = $name;
        $json->gpio = intval($gpio);
        $json->runtime = intval($runtime);
        $json->enabled = $enabled;
        $json->autooff = $autooff;
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
     * @param Zone $zone - The zone we are updating.
     * @return string
     */
    static function getUpdateQuery(Zone $zone)
    {
        $data = json_decode($zone->toJson(),true);
        $name = $data['name'];
        $gpio = $data['gpio'];
        $runtime = $data['runtime'];
        $enabled = $data['enabled'];
        $autooff = $data['autooff'];
        $id = $data['id'];
        $query = "UPDATE Systems SET `Name`='" . $name . "', `GPIO`=" . $gpio . ", `Time`=" . $runtime .
            ", `Enabled`=" . $enabled . ", `Autooff`=" . $autooff . " WHERE id=" . $id;
        return $query;
    }

    /**
     * @param Zone $zone - The zone we are creating.
     * @return string
     */
    static function getInsertQuery(Zone $zone)
    {
        $data = json_decode($zone->toJson(),true);
        $name = $data['name'];
        $gpio = $data['gpio'];
        $runtime = $data['runtime'];
        $enabled = $data['enabled'];
        $autooff = $data['autooff'];
        $query = "INSERT INTO `Systems` (`Name`, `GPIO`, `Time`, `Enabled`, `Autooff`) VALUES ('" . $name . "','" . $gpio . "','" . $runtime . "'," . $enabled . "," . $autooff . ")";
        return $query;
    }

    static function getDeleteQuery(Zone $zone)
    {
        $data = $zone->toJson();
        $id = json_decode($data,true)["id"];
        $query = "DELETE FROM `Systems` WHERE `id` =" . $id;
        return $query;
    }
}