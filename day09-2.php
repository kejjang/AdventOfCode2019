<?php
include_once __DIR__ . "/autoload.php";

$intcode = Reader::read('day09');

$computer = new Intcode($intcode);
$computer->setInputs(2);

while ($computer->getStatus() != Intcode::STATUS_HALT) {
    $computer->calc();
}

echo $computer->getSignal(), "\n";
