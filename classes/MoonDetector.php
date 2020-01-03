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

    private function addValues(&$val1, $val2)
    {
        for ($i = 0; $i < count($val2); $i++) {
            $val1[$i] += $val2[$i];
        }

        return $val1;
    }

    public function motionSimulator($steps)
    {
        for ($i = 0; $i < $steps; $i++) {
            $temp_moons = $this->moons;

            foreach ($temp_moons as $moon_idx1 => $moon) {
                $vel_diff = [0, 0, 0];
                foreach ($temp_moons as $moon_idx2 => $moon2) {
                    if ($moon_idx1 === $moon_idx2) {
                        continue;
                    } else {
                        for ($j = 0; $j < 3; $j++) {
                            if ($moon['pos'][$j] < $moon2['pos'][$j]) {
                                $vel_diff[$j] += 1;
                            } elseif ($moon['pos'][$j] > $moon2['pos'][$j]) {
                                $vel_diff[$j] -= 1;
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
        $count = 0;
        do {
            $this->motionSimulator(1);
            $count++;
            if ($count % 10000 == 0) {
                echo $count, "\n";
            }
        } while (!$this->isAtInitPos($count));
        return $count;
    }

    private function isAtInitPos()
    {
        $valid = 0;
        foreach ($this->moons as $moon) {
            for ($i = 0; $i < 3; $i++) {
                if ($moon['pos'][$i] == $moon['pos_init'][$i]) {
                    $valid++;
                }
                if ($moon['vel'][$i] == 0) {
                    $valid++;
                }
            }
        }

        return ($valid === 6 * count($this->moons)) ? true : false;
    }
}
