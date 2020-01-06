<?php
class MoonDetector
{
    public $moons;

    public function __construct($data = false)
    {
        $this->moons = [];

        if ($data !== false) {
            $this->setData($data);
        }

        return true;
    }

    public function setData($data = false)
    {
        if ($data !== false) {
            return $this->parseData($data);
        } else {
            return false;
        }
    }

    private function parseData($data)
    {
        foreach ($data as $line) {
            $moon = [
                'pos_init' => [0, 0, 0],
                'pos' => [0, 0, 0],
                'vel' => [0, 0, 0]
            ];
            $index = [
                'x' => 0,
                'y' => 1,
                'z' => 2
            ];

            $positions = explode(',', str_replace(['<', '>'], '', $line));
            foreach ($positions as $pos) {
                $pos = explode('=', $pos);
                $moon['pos'][$index[trim($pos[0])]] = trim($pos[1]);
            }

            $moon['pos_init'] = $moon['pos'];

            $this->moons[] = $moon;
        }

        return true;
    }

    private function reset()
    {
        foreach ($this->moons as $moon_idx => $moon) {
            $this->moons[$moon_idx]['pos'] = $this->moons[$moon_idx]['pos_init'];
            $this->moons[$moon_idx]['vel'] = [0, 0, 0];
        }
        return true;
    }

    private function addValues(&$val1, $val2)
    {
        for ($i = 0; $i < count($val2); $i++) {
            $val1[$i] += $val2[$i];
        }

        return $val1;
    }

    public function motionSimulator($steps, $axis = false)
    {
        for ($i = 0; $i < $steps; $i++) {
            $temp_moons = $this->moons;

            foreach ($temp_moons as $moon_idx1 => $moon) {
                $vel_diff = [0, 0, 0];
                foreach ($temp_moons as $moon_idx2 => $moon2) {
                    if ($moon_idx1 === $moon_idx2) {
                        continue;
                    } else {
                        if ($axis === false) {
                            for ($j = 0; $j < 3; $j++) {
                                if ($moon['pos'][$j] < $moon2['pos'][$j]) {
                                    $vel_diff[$j] += 1;
                                } elseif ($moon['pos'][$j] > $moon2['pos'][$j]) {
                                    $vel_diff[$j] -= 1;
                                }
                            }
                        } else {
                            if ($moon['pos'][$axis] < $moon2['pos'][$axis]) {
                                $vel_diff[$axis] += 1;
                            } elseif ($moon['pos'][$axis] > $moon2['pos'][$axis]) {
                                $vel_diff[$axis] -= 1;
                            }
                        }
                    }
                }
                $this->addValues($this->moons[$moon_idx1]['vel'], $vel_diff);
                $this->addValues($this->moons[$moon_idx1]['pos'], $this->moons[$moon_idx1]['vel']);
            }
        }
    }

    public function getEnergy()
    {
        $energy = 0;
        foreach ($this->moons as $moon) {
            $pot = abs($moon['pos'][0]) + abs($moon['pos'][1]) + abs($moon['pos'][2]);
            $kin = abs($moon['vel'][0]) + abs($moon['vel'][1]) + abs($moon['vel'][2]);
            $energy += $pot * $kin;
        }

        return $energy;
    }

    public function calcBackInitPosSteps()
    {
        $all_counts = [];

        for ($axis = 0; $axis < 3; $axis++) {
            $count = 0;
            $this->reset();

            do {
                $this->motionSimulator(1, $axis);
                $count++;
            } while (!$this->isAtInitPosAxis($axis));

            $all_counts[] = $count;
        }

        return $this->getGroupLCM($all_counts);
    }

    private function isAtInitPosAxis($axis)
    {
        foreach ($this->moons as $moon) {
            if ($moon['pos'][$axis] != $moon['pos_init'][$axis]) {
                return false;
            }

            if ($moon['vel'][$axis] != 0) {
                return false;
            }
        }

        return true;
    }

    private function getGCD($a, $b)
    {
        // Greatest Common Divisor
        if ($a == 0) {
            return $b;
        }
        return $this->getGCD($b % $a, $a);
    }

    private function getLCM($a, $b)
    {
        // least common multiple
        // https://www.geeksforgeeks.org/program-to-find-lcm-of-two-numbers/
        return ($a * $b) / $this->getGCD($a, $b);
    }

    public function getGroupLCM($group)
    {
        $a = array_shift($group);
        while (count($group) > 0) {
            $b = array_shift($group);
            $a = $this->getLCM($a, $b);
        }
        return $a;
    }
}
