<?php
ini_set('xdebug.max_nesting_level', 1000);
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $map = Reader::split("\n", 'test/day06-test2');
    $orbit_map = new OrbitMap($map);
    Test::equal('test 2', $orbit_map->getMinimalTransferBetween('YOU', 'SAN'), 4);

    echo "\n";
}
// test end

$map = Reader::split("\n", 'day06');

$orbit_map = new OrbitMap($map);

echo $orbit_map->getMinimalTransferBetween('YOU', 'SAN'), "\n";
