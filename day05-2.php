<?php
include_once __DIR__ . "/autoload.php";

$intcode = Reader::read('day05');

$computer = new Intcode($intcode);
$computer->setInputs(5);

while ($computer->getStatus() != Intcode::STATUS_HALT) {
    $computer->calc();
}

echo $computer->getSignal(), "\n";
