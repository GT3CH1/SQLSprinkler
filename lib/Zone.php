<?php
// Copyright 2021 Gavin Pease

class Zone
{
    /**
     * The name of this zone.
     * @var string
     */
    private $name;

    /**
     * The gpio pin for this zone
     * @var int
     */
    private $gpio;


    /**
     * The time (in minutes) that this zone will run for.
     * @var int
     */
    private $runtime;


    /**
     * Whether or not this zone is sat to run during the system schedule.
     * @var bool
     */
    private $enabled;


    /**
     * Whether or not this zone automatically turns off after being turned on from the webpage.
     * @var bool
     */
    private $autooff;


    /**
     * The unique id for this zone.
     * @var int
     */
    private $id;

    /**
     * The json value of this zone.
     * @var string
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
    public function __constructor($name, $gpio, $runtime, $enabled, $autooff, $id)
    {
        $this->name = $name;
        $this->gpio = intval($gpio);
        $this->runtime = intval($runtime);
        $this->enabled = boolval($enabled);
        $this->autooff = boolval($autooff);
        $this->id = intval($id);
        $this->json = array();
        $this->json->name = $this->name;
        $this->json->gpio = $this->gpio;
        $this->json->runtime = $this->runtime;
        $this->json->enabled = $this->enabled;
        $this->json->autooff = $this->autooff;
        $this->json->id = $this->id;
        $this->json = json_encode($this->json);
    }

    /**
     * Gets the zone's name.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the zone's gpio pin.
     * @return int
     */
    public function getGpio()
    {
        return $this->gpio;
    }

    /**
     * Gets the zone's run time.
     * @return int
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
     * Gets whether or not this zone is enabled for running during the system schedule.
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Gets whether or not this zone is set to automatically turn off when turned on from the web page.
     * @return bool
     */
    public function getAutooff()
    {
        return $this->autooff;
    }

    /**
     * Gets the id of this zone.
     * @return
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Pretty-prints this system in JSON.
     * @return string
     */
    public function __toString()
    {
        return $this->json;
    }
}