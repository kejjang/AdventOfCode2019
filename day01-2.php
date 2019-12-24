<?php
include_once __DIR__ . "/autoload.php";

$masses = Reader::split("\n", 'day01');

function calcFuels($mass)
{
    $fuels = floor($mass / 3) - 2;
    if ($fuels <= 0) {
        return 0;
    } else {
        return $fuels + calcFuels($fuels);
    }
}

$fuels = array_reduce($masses, function ($carry, $mass) {
    return $carry + calcFuels($mass);
}, 0);

echo $fuels, "\n";
