<?php
class AsteroidDetector
{
    private $map;
    public $asteroids = [];

    public function __construct($map = false)
    {
        if ($map !== false) {
            $this->setMap($map);
        }
        return true;
    }

    public function setMap($map = false)
    {
        if ($map !== false) {
            $result = $this->parseMap($map);
            if ($result) {
                return $this->parseCoordinates();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function parseMap($map)
    {
        $this->map = array_map(function ($line) {
            return preg_split('//', $line, -1, PREG_SPLIT_NO_EMPTY);
        }, $map);

        if (count($this->map) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function genKey($x, $y, $reverse = false)
    {
        if ($reverse) {
            [$x, $y] = [$y, $x];
        }

        $prefix_x = $x < 0 ? 'N' : 'P';
        $prefix_y = $y < 0 ? 'N' : 'P';

        $key = $prefix_x . abs($x) . $prefix_y . abs($y);
        return $key;
    }

    private function parseCoordinates()
    {
        $this->asteroids = [];
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $obj) {
                if ($obj == '#') {
                    $key = $this->genKey($x, $y);
                    $this->asteroids[$key] = ['coordinate' => [$x, $y], 'observable' => 0];
                }
            }
        }

        if (count($this->asteroids) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getAsteroids()
    {
        return $this->asteroids;
    }

    public function calcObservableCount()
    {
        foreach ($this->asteroids as $idx1 => $astro1) {
            $x1 = $astro1['coordinate'][0];
            $y1 = $astro1['coordinate'][1];

            foreach ($this->asteroids as $idx2 => $astro2) {
                if ($idx1 == $idx2) {
                    continue;
                }
                $x2 = $astro2['coordinate'][0];
                $y2 = $astro2['coordinate'][1];

                if ($this->clearBetweenUs($x1, $y1, $x2, $y2)) {
                    // echo $x1, ', ', $y1, ', ', $x2, ', ', $y2, "\n";
                    $this->asteroids[$idx1]['observable']++;
                }
            }
        }
    }

    private function genParams($x1, $y1, $x2, $y2)
    {
        $x_diff = $x2 - $x1;
        $y_diff = $y2 - $y1;
        $reverse = false;

        if (abs($x_diff) < abs($y_diff)) {
            [$x1, $y1, $x2, $y2, $x_diff, $y_diff] = [$y1, $x1, $y2, $x2, $y_diff, $x_diff];
            $reverse = true;
        }

        if ($x_diff < 0) {
            [$x1, $x2, $y1, $y2] = [$x2, $x1, $y2, $y1];
            $x_diff *= -1;
            $y_diff *= -1;
        }

        return [[$x1, $x2], $y1, ($y_diff / $x_diff), $reverse];
    }

    private function clearBetweenUs($x1, $y1, $x2, $y2)
    {
        $check_coords = [];

        [$key1_range, $key2_base, $seg, $reverse] = $this->genParams($x1, $y1, $x2, $y2);

        for ($key1 = $key1_range[0]; $key1 <= $key1_range[1]; $key1++) {
            $steps = $key1 - $key1_range[0];
            $key2 = $key2_base + $steps * $seg;
            if (ctype_digit(strval($key2))) { // check is integer
                $check_coords[] = $this->genKey($key1, $key2, $reverse);
            }
        }

        array_pop($check_coords);
        array_shift($check_coords);

        $clear = true;

        $all_keys = array_keys($this->asteroids);
        if (count(array_intersect($all_keys, $check_coords)) > 0) {
            $clear = false;
        }

        return $clear;
    }

    public function getBestLocation()
    {
        $aster = array_values($this->asteroids);

        usort($aster, function ($a, $b) {
            if ($a['observable'] == $b['observable']) {
                return 0;
            } else {
                return $a['observable'] < $b['observable'] ? 1 : -1;
            }
        });

        return ['coordinate' => implode(',', $aster[0]['coordinate']), 'count' => $aster[0]['observable']];
    }
}
