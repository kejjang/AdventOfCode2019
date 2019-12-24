<?php
class WirePlate
{
    private $ignore_step = true;
    private $debug = false;
    private $wire_num = -1;
    private $loc = [];
    private $cross = [];

    public function __construct($ignore_step = true, $debug = false)
    {
        $this->setIgnoreStep($ignore_step);
        $this->setDebug($debug);
        $this->wire_num = -1;
        $this->loc = [];
        $this->cross = [];

        return true;
    }

    public function setIgnoreStep($ignore_step = true)
    {
        $this->ignore_step = $ignore_step;

        return true;
    }

    public function setDebug($debug = false)
    {
        $this->debug = $debug;

        return true;
    }

    public function markWires($wires)
    {
        if (is_array($wires)) {
            foreach ($wires as $wire) {
                $this->markWire($wire);
            }
        } else {
            $this->markWire($wires);
        }

        return true;
    }

    public function markWire($wire)
    {
        $this->wire_num++;

        $parts = explode(',', $wire);
        $x = 0;
        $y = 0;
        $steps = 0;

        if ($this->debug) {
            echo "wire $this->wire_num:\n";
            echo "($x,$y)\n";
        }

        foreach ($parts as $part) {
            $direction = substr($part, 0, 1);
            $distance = substr($part, 1);
            switch ($direction) {
                case 'U':
                    for ($i = 0; $i < $distance; $i++) {
                        $y++;
                        $this->loc[$x][$y][$this->wire_num] = $this->ignore_step ? 1 : ++$steps;
                    }
                    break;
                case 'D':
                    for ($i = 0; $i < $distance; $i++) {
                        $y--;
                        $this->loc[$x][$y][$this->wire_num] = $this->ignore_step ? 1 : ++$steps;
                    }
                    break;
                case 'L':
                    for ($i = 0; $i < $distance; $i++) {
                        $x--;
                        $this->loc[$x][$y][$this->wire_num] = $this->ignore_step ? 1 : ++$steps;
                    }
                    break;
                case 'R':
                    for ($i = 0; $i < $distance; $i++) {
                        $x++;
                        $this->loc[$x][$y][$this->wire_num] = $this->ignore_step ? 1 : ++$steps;
                    }
                    break;
            }
            if ($this->debug) {
                echo "($x,$y)\n";
            }
        }
        if ($this->debug) {
            echo "\n";
        }
    }

    public function calcCross()
    {
        $this->cross = [];

        foreach ($this->loc as $x => $spot) {
            foreach ($spot as $y => $value) {
                if (count($value) > 1) {
                    // echo array_sum($value), "\n";
                    // echo 'x: ', $x, ', y: ', $y, ', distance: ', (abs($x) + abs($y)), "\n";
                    $this->cross[] = [$x, $y, (abs($x) + abs($y)), ($value[0] + $value[1])];
                }
            }
        }

        return true;
    }

    public function getClosetCrossInfo()
    {
        $this->calcCross();
        $compare_index = $this->ignore_step ? 2 : 3;

        usort($this->cross, function ($a, $b) use ($compare_index) {
            if ($a[$compare_index] == $b[$compare_index]) {
                return 0;
            } else {
                return $a[$compare_index] < $b[$compare_index] ? -1 : 1;
            }
        });

        return $this->cross[0];
    }
}
