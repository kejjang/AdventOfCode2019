<?php
include_once __DIR__ . "/autoload.php";

$intcode = Reader::read('day11');

$robot = new PaintRobot($intcode);
$robot->setInputs(0);

$robot->run();

echo $robot->getMarkedCount(), "\n";