<?php
include_once __DIR__ . "/autoload.php";

$run_test = false;

// test start
if ($run_test) {
    $data = explode("\n", "<x=-1, y=0, z=2>\n<x=2, y=-10, z=-7>\n<x=4, y=-8, z=8>\n<x=3, y=5, z=-1>");
    $detector = new MoonDetector($data);
    $detector->motionSimulator(10);
    Test::equal('test 1', $detector->getEnergy(), 179);

    $data = explode("\n", "<x=-8, y=-10, z=0>\n<x=5, y=5, z=10>\n<x=2, y=-7, z=3>\n<x=9, y=-8, z=-3>");
    $detector = new MoonDetector($data);
    $detector->motionSimulator(100);
    Test::equal('test 2', $detector->getEnergy(), 1940);

    echo "\n";
}
// test end

$data = Reader::split("\n", 'day12');

$detector = new MoonDetector($data);
$detector->motionSimulator(1000);
echo $detector->getEnergy(), "\n";
