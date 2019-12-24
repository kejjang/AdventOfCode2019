<?php
ini_set("memory_limit", "256M");
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $wires = array(
        'R75,D30,R83,U83,L12,D49,R71,U7,L72',
        'U62,R66,U55,R34,D71,R55,D58,R83' // = distance 159
    );
    $plate = new WirePlate(false, false);
    $plate->markWires($wires);

    $info = $plate->getClosetCrossInfo();
    Test::equal('test 1', $info[3], 610);

    $wires = array(
        'R98,U47,R26,D63,R33,U87,L62,D20,R33,U53,R51',
        'U98,R91,D20,R16,D67,R40,U7,R15,U6,R7' // = distance 135
    );
    $plate = new WirePlate(false, false);
    $plate->markWires($wires);

    $info = $plate->getClosetCrossInfo();
    Test::equal('test 2', $info[3], 410);

    echo "\n";
}
// test end

$wires = Reader::split("\n", 'day03');
// print_r($wires);

$plate = new WirePlate(false, false);
$plate->markWires($wires);

$info = $plate->getClosetCrossInfo();

echo 'x: ', $info[0], ', y: ', $info[1], ', distance: ', $info[2], ', steps: ', $info[3], "\n";
