<?php
class OrbitMap
{
    private $map;
    private $routes;

    public function __construct($map)
    {
        $map = array_map(function ($line) {
            return explode(')', $line);
        }, $map);
        $this->map = $map;

        $this->routes = [];
        foreach ($map as $item) {
            $a = SpaceObject::getInstance($item[0]);
            $b = SpaceObject::getInstance($item[1]);

            $a->setOrbitFrom($b);
            $b->setOrbitTo($a);

            $this->routes[$item[0]] = $a;
            $this->routes[$item[1]] = $b;
        }

        return true;
    }

    private function getNumber($obj)
    {
        if ($obj->orbit_to == null) {
            return 0;
        } else {
            return 1 + $this->getNumber($obj->orbit_to);
        }
    }

    public function getTransferNum()
    {
        $total = 0;
        foreach ($this->routes as $route) {
            $total += $this->getNumber($route);
        }
        return $total;
    }

    private function getRoute($obj)
    {
        if ($obj->orbit_to == null) {
            return $obj->name;
        } else {
            return $this->getRoute($obj->orbit_to) . ')' . $obj->name;
        }
    }

    public function getMinimalTransferBetween($from, $to)
    {
        $route1 = $this->getRoute($this->routes[$from]);
        // echo $route1, "\n";
        $route1 = explode(')', $route1);

        $route2 = $this->getRoute($this->routes[$to]);
        // echo $route2, "\n";
        $route2 = explode(')', $route2);

        while ($route1[0] == $route2[0]) {
            array_shift($route1);
            array_shift($route2);
        }

        return count($route1) - 2 + count($route2) - 2 + 2;
    }
}
