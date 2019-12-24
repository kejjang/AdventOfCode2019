<?php
include_once __DIR__ . "/autoload.php";

$masses = Reader::split("\n", 'day01');

$fuels = array_reduce($masses, function ($carry, $mass) {
    return $carry + floor($mass / 3) - 2;
}, 0);

echo $fuels, "\n";
