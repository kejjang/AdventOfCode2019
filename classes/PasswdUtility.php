<?php
class PasswdUtility
{
    private static function isDouble($passwd)
    {
        $digits = preg_split('//', $passwd, -1, PREG_SPLIT_NO_EMPTY);
        $digits = array_unique($digits);
        if (count($digits) == 6) {
            return false;
        } else {
            return true;
        }
    }

    private static function isNotDecrease($passwd)
    {
        $digits = preg_split('//', $passwd, -1, PREG_SPLIT_NO_EMPTY);
        sort($digits);
        $digits = implode('', $digits);
        if ($passwd == $digits) {
            return true;
        } else {
            return false;
        }
    }

    private static function isNotPartOfLargerGroup($passwd)
    {
        $digits = preg_split('//', $passwd, -1, PREG_SPLIT_NO_EMPTY);
        $counts = [];
        foreach ($digits as $d) {
            if (isset($counts[$d])) {
                $counts[$d]++;
            } else {
                $counts[$d] = 1;
            }
        }
        $is_valid = false;
        foreach ($counts as $c) {
            if ($c == 2) {
                $is_valid = true;
            }
        }

        return $is_valid;
    }

    public static function guess($ranges, $notPartOfLargerGroup = false)
    {
        $possible = [];

        for ($i = $ranges[0]; $i <= $ranges[1]; $i++) {
            if (self::isDouble($i) && self::isNotDecrease($i)) {
                if ($notPartOfLargerGroup && !self::isNotPartOfLargerGroup($i)) {
                    continue;
                }
                $possible[] = $i;
            }
        }

        return $possible;
    }

    public static function guessCount($ranges, $notPartOfLargerGroup = false)
    {
        return count(self::guess($ranges, $notPartOfLargerGroup));
    }
}
