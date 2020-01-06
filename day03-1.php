<?php
ini_set("memory_limit", "256M");
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $wires = Reader::split("\n", 'test/day03-test1');
    $plate = new WirePlate(true, false);
    $plate->markWires($wires);

    $info = $plate->getClosetCrossInfo();
    Test::equal('test 1', $info[2], 159); // = distance 159

    $wires = Reader::split("\n", 'test/day03-test2');
    $plate = new WirePlate(true, false);
    $plate->markWires($wires);

    $info = $plate->getClosetCrossInfo();
    Test::equal('test 2', $info[2], 135); // = distance 135

    echo "\n";
}
// test end

$wires = Reader::split("\n", 'day03');
// print_r($wires);

$plate = new WirePlate(true, false);
$plate->markWires($wires);

$info = $plate->getClosetCrossInfo();

echo 'x: ', $info[0], ', y: ', $info[1], ', distance: ', $info[2], "\n";
