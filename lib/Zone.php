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
        $status  = boolval(exec(' which gpio && gpio -g read ' . $gpio . ' || echo 1'));
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

    public function getData(){
        return $this->json;
    }

    public function __toString(){
        return $this->toJson();
    }
}