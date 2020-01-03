<?php

use Intcode as Intcode;

class PaintRobot
{
    private $computer;
    private $direction; // 0: up, 1: right, 2: down, 3: left
    private $coords;
    private $panels;

    public function __construct($intcode = false)
    {
        if ($intcode !== false) {
            $this->init_computer($intcode);
        }
        $this->direction = 0;
        $this->coords = [0, 0];
        $this->panels = [];
        return true;
    }

    public function init_computer($intcode = false)
    {
        if ($intcode !== false) {
            $this->computer = new Intcode($intcode);
        }
        return true;
    }

    public function setInputs($inputs)
    {
        return $this->computer->setInputs($inputs);
    }

    public function run()
    {
        $op_type = -1;

        while ($this->computer->getStatus() != Intcode::STATUS_HALT) {
            list($op, $status) = $this->computer->calc();
            if ($op == '04') {
                $op_type = ($op_type + 1) % 2;
                $output = $this->computer->getSignal();

                switch ($op_type) {
                    case 0:
                        $this->mark($output);
                        break;
                    case 1:
                        $this->move($output);
                        $this->computer->setInputs($this->readPanelColor());
                        break;
                }
            }
        }

        return true;
    }

    private function mark($input)
    {
        $color = ($input == 0 ? '.' : '#');
        $this->panels[$this->coords[0]][$this->coords[1]] = $color;

        return true;
    }

    private function move($input)
    {
        if ($input == 0) {
            $next = 3;
        } elseif ($input == 1) {
            $next = 1;
        }
        $this->direction = ($this->direction + $next) % 4;
        $this->getNextStep();

        return true;
    }

    private function getNextStep()
    {
        $x_diff = 0;
        $y_diff = 0;

        switch ($this->direction) {
            case 0:
                $y_diff--;
                break;
            case 1:
                $x_diff++;
                break;
            case 2:
                $y_diff++;
                break;
            case 3:
                $x_diff--;
                break;
        }

        $this->coords = [$this->coords[0] + $x_diff, $this->coords[1] + $y_diff];

        return true;
    }

    private function readPanelColor()
    {
        if (isset($this->panels[$this->coords[0]]) && isset($this->panels[$this->coords[0]][$this->coords[1]])) {
            $color = ($this->panels[$this->coords[0]][$this->coords[1]] == '#') ? 1 : 0;
        } else {
            $color = 0;
        }

        return $color;
    }

    public function getMarkedCount()
    {
        $count = 0;
        foreach ($this->panels as $x) {
            $count += count($x);
        }

        return $count;
    }

    public function paint($black = false)
    {
        if ($black === false) {
            $black = '.';
        }
        if (strlen($black) > 1) {
            $black = substr($black, 0, 1);
        }

        $x = range(min(array_keys($this->panels)), max(array_keys($this->panels)));
        $ys = [];
        foreach ($this->panels as $cols) {
            $ys[] = min(array_keys($cols));
            $ys[] = max(array_keys($cols));
        }
        $y = range(min($ys), max($ys));

        foreach ($y as $i) {
            foreach ($x as $j) {
                if (isset($this->panels[$j]) && isset($this->panels[$j][$i])) {
                    echo str_replace('.', $black, $this->panels[$j][$i]);
                } else {
                    echo $black;
                }
            }
            echo "\n";
        }

        return true;
    }
}
