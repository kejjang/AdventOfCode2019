<?php
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $data = Reader::split("\n", 'test/day12-test1');
    $detector = new MoonDetector($data);
    $detector->motionSimulator(10);
    Test::equal('test 1', $detector->getEnergy(), 179);

    $data = Reader::split("\n", 'test/day12-test2');
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
