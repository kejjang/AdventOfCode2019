<?php
ini_set('xdebug.max_nesting_level', 1000);
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $map = Reader::split("\n", 'test/day06-test1');
    $orbit_map = new OrbitMap($map);
    Test::equal('test 1', $orbit_map->getTransferNum(), 42);

    echo "\n";
}
// test end

$map = Reader::split("\n", 'day06');

$orbit_map = new OrbitMap($map);

echo $orbit_map->getTransferNum(), "\n";
