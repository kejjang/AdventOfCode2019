<?php
class PhaseUtility
{
    const MODE_GENERAL = 0;
    const MODE_FEEDBACK = 1;

    public static function swap($ary, $idx1, $idx2)
    {
        [$ary[$idx1], $ary[$idx2]] = [$ary[$idx2], $ary[$idx1]];
        return $ary;
    }

    public static function factorial($n)
    {
        return ($n <= 1) ? 1 : $n * self::factorial($n - 1);
    }

    public static function gen($mode)
    {
        switch ($mode) {
            case self::MODE_GENERAL:
                $seeds = range(0, 4);
                break;
            case self::MODE_FEEDBACK:
                $seeds = range(5, 9);
                break;
        }

        $phases = [];

        $phases[] = implode(',', $seeds);
        $pos = count($seeds);

        foreach ($seeds as $seed) {
            foreach ($phases as $phase) {
                $last = explode(',', $phase);
                $loc = array_search($seed, $last);
                while ($loc < ($pos - 1)) {
                    $phases[] = implode(',', self::swap($last, $loc, $loc + 1));
                    $count = count($phases);
                    $last = explode(',', $phases[$count - 1]);
                    $loc = array_search($seed, $last);
                }
            }
            $phases = array_values(array_unique($phases));
        }

        $phases = array_values(array_unique($phases));
        sort($phases);

        foreach ($phases as $idx => $phase) {
            $phases[$idx] = explode(',', $phase);
        }

        return $phases;
    }
}
