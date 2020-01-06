<?php
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $map = Reader::split("\n", 'test/day10-test1');
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 1', $detector->getBestLocation()['coordinate'], '3,4');

    $map = Reader::split("\n", 'test/day10-test2');
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 2', $detector->getBestLocation()['coordinate'], '5,8');

    $map = Reader::split("\n", 'test/day10-test3');
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 3', $detector->getBestLocation()['coordinate'], '1,2');

    $map = Reader::split("\n", 'test/day10-test4');
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 4', $detector->getBestLocation()['coordinate'], '6,3');

    $map = Reader::split("\n", 'test/day10-test5');
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 5', $detector->getBestLocation()['coordinate'], '11,13');

    $best_location = $detector->getBestLocation();
    $vape_orders = $detector->getVapeOrder($best_location['coordinate']);

    $coords = $vape_orders[199];
    Test::equal('test 6', 100 * $coords[0] + $coords[1], '802');

    echo "\n";
}
// test end

$map = Reader::split("\n", 'day10');

$detector = new AsteroidDetector($map);
$detector->calcObservableCount();
$best_location = $detector->getBestLocation();

echo 'coords: ', $best_location['coordinate'], "\n";
echo 'count: ', $best_location['count'], "\n";

$vape_orders = $detector->getVapeOrder($best_location['coordinate']);
$coords = $vape_orders[199];

echo 100 * $coords[0] + $coords[1], "\n";
