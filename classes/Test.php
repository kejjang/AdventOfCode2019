<?php
class Test
{
    public static function equal($name, $test, $compare)
    {
        if ($test == $compare) {
            echo $name, ' (', __FUNCTION__, '): is passed', "\n";
        } else {
            echo $name, ' (', __FUNCTION__, '): is NOT pass', "\n";
        }
    }
}
