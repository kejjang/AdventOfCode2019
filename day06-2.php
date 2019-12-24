<?php
ini_set('xdebug.max_nesting_level', 1000);
include_once __DIR__ . "/autoload.php";
$run_test = true;

// test start
if ($run_test) {
    $map = explode("\n", "COM)B\nB)C\nC)D\nD)E\nE)F\nB)G\nG)H\nD)I\nE)J\nJ)K\nK)L\nK)YOU\nI)SAN");
    $orbit_map = new OrbitMap($map);
    Test::equal('test 1', $orbit_map->getMinimalTransferBetween('YOU', 'SAN'), 4);

    echo "\n";
}
// test end

$map = Reader::split("\n", 'day06');

$orbit_map = new OrbitMap($map);

echo $orbit_map->getMinimalTransferBetween('YOU', 'SAN'), "\n";
