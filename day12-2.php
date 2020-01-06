<?php
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $data = Reader::split("\n", 'test/day12-test1');
    $detector = new MoonDetector($data);
    $steps = $detector->calcBackInitPosSteps();
    Test::equal('test 1', $steps, 2772);

    $data = Reader::split("\n", 'test/day12-test2');
    $detector = new MoonDetector($data);
    $steps = $detector->calcBackInitPosSteps();
    Test::equal('test 2', $steps, 4686774924);

    echo "\n";
}
// test end

$data = Reader::split("\n", 'day12');

$detector = new MoonDetector($data);
echo $detector->calcBackInitPosSteps(), "\n";
