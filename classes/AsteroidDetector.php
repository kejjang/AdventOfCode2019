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

    private function genQuadrantKey($x, $y, $reverse = false)
    {
        if ($reverse) {
            [$x, $y] = [$y, $x];
        }

        $key_x = $x == 0 ? 'Z' : ($x < 0 ? 'N' : 'P');
        $key_y = $y == 0 ? 'Z' : ($y < 0 ? 'N' : 'P');

        $key = $key_x . $key_y;
        return $key;
    }

    private function parseCoordinates()
    {
        $this->asteroids = [];
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $obj) {
                if ($obj == '#') {
                    $key = $this->genKey($x, $y);
                    $this->asteroids[$key] = ['coordinate' => [$x, $y], 'observable' => []];
                }
            }
        }

        if (count($this->asteroids) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getAsteroids($coords = false)
    {
        if (is_string($coords)) {
            $coords = explode(',', $coords);
        }
        if ($coords) {
            return $this->asteroids[$this->genKey($coords[0], $coords[1])];
        } else {
            return $this->asteroids;
        }
    }

    public function calcObservableCount()
    {
        foreach ($this->asteroids as $idx1 => $astro1) {
            $this->asteroids[$idx1]['observable'] = [];

            $x1 = $astro1['coordinate'][0];
            $y1 = $astro1['coordinate'][1];

            foreach ($this->asteroids as $idx2 => $astro2) {
                if ($idx1 == $idx2) {
                    continue;
                }
                $x2 = $astro2['coordinate'][0];
                $y2 = $astro2['coordinate'][1];

                if ($this->clearBetweenUs($x1, $y1, $x2, $y2)) {
                    $this->asteroids[$idx1]['observable'][] = [$x2, $y2];
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
            if (count($a['observable']) == count($b['observable'])) {
                return 0;
            } else {
                return count($a['observable']) < count($b['observable']) ? 1 : -1;
            }
        });

        return ['coordinate' => implode(',', $aster[0]['coordinate']), 'count' => count($aster[0]['observable'])];
    }

    private function vapeAsteroids($asters)
    {
        foreach ($asters as $aster) {
            $key = $this->genKey($aster[0], $aster[1]);
            unset($this->asteroids[$key]);
        }
    }

    public function getVapeOrder($coords = false)
    {
        if ($coords == false) {
            return false;
        }

        $orders = [];
        $asteroids_data_backup = $this->asteroids;

        while (count($this->asteroids) > 1) {
            $round_orders = $this->getVapeOrderRound($coords);
            $orders = array_merge($orders, $round_orders);
            $this->vapeAsteroids($round_orders);
            $this->calcObservableCount();
        }

        $this->asteroids = $asteroids_data_backup;
        return $orders;
    }

    private function getVapeOrderRound($coords = false)
    {
        if ($coords == false) {
            return false;
        }

        $orders = [];
        $divisions = [
            0 => [], // up
            1 => [], // 1st quadrant
            2 => [], // right
            3 => [], // 4th quadrant
            4 => [], // down
            5 => [], // 3rd quadrant
            6 => [], // left
            7 => [], // 2nd quadrant
        ];

        $asteroid_data = $this->getAsteroids($coords);

        $base_x = $asteroid_data['coordinate'][0];
        $base_y = $asteroid_data['coordinate'][1];

        foreach ($asteroid_data['observable'] as $aster) {
            $relative_x = $aster[0] - $base_x;
            $relative_y = $aster[1] - $base_y;

            $quadrant_key = $this->genQuadrantKey($relative_x, $relative_y);

            switch ($quadrant_key) {
                case 'ZN':
                    $divisions[0][] = ['coords' => [$relative_x, $relative_y], 'angle_factor' => abs($relative_y)];
                    break;
                case 'PN':
                    $divisions[1][] = ['coords' => [$relative_x, $relative_y], 'angle_factor' => abs(floatval($relative_y / $relative_x))];
                    break;
                case 'PZ':
                    $divisions[2][] = ['coords' => [$relative_x, $relative_y], 'angle_factor' => abs($relative_x)];
                    break;
                case 'PP':
                    $divisions[3][] = ['coords' => [$relative_x, $relative_y], 'angle_factor' => abs(floatval($relative_x / $relative_y))];
                    break;
                case 'ZP':
                    $divisions[4][] = ['coords' => [$relative_x, $relative_y], 'angle_factor' => abs($relative_y)];
                    break;
                case 'NP':
                    $divisions[5][] = ['coords' => [$relative_x, $relative_y], 'angle_factor' => abs(floatval($relative_y / $relative_x))];
                    break;
                case 'NZ':
                    $divisions[6][] = ['coords' => [$relative_x, $relative_y], 'angle_factor' => abs($relative_x)];
                    break;
                case 'NN':
                    $divisions[7][] = ['coords' => [$relative_x, $relative_y], 'angle_factor' => abs(floatval($relative_x / $relative_y))];
                    break;
            }
        }

        foreach ($divisions as $div) {
            usort($div, function ($a, $b) {
                if ($a['angle_factor'] == $b['angle_factor']) {
                    return 0;
                } else {
                    return $a['angle_factor'] < $b['angle_factor'] ? 1 : -1;
                }
            });

            $div = array_map(function ($aster) use ($base_x, $base_y) {
                return [$aster['coords'][0] + $base_x, $aster['coords'][1] + $base_y];
            }, $div);

            $orders = array_merge($orders, $div);
        }

        return $orders;
    }
}
