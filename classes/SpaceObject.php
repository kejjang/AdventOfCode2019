<?php
class SpaceObject
{
    private static $instances = [];
    public $name;
    public $orbit_to;
    public $orbit_from = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setOrbitTo(SpaceObject $orbit_to)
    {
        $this->orbit_to = $orbit_to;
    }

    public function setOrbitFrom(SpaceObject $orbit_from)
    {
        $this->orbit_from[] = $orbit_from;
    }

    public static function getInstance($name)
    {
        if (!array_key_exists($name, self::$instances)) {
            self::$instances[$name] = new SpaceObject($name);
        }
        return self::$instances[$name];
    }
}
