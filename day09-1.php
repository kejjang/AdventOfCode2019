<?php
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $intcode = Reader::read('test/day09-test1');
    $computer = new Intcode($intcode);

    $output = [];
    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        [$op, $status] = $computer->calc();
        if ($op == '04') {
            $output[] = $computer->getSignal();
        }
    }
    $output = implode(',', $output);
    Test::equal('test 1', $output, $intcode);

    $intcode = Reader::read('test/day09-test2');
    $computer = new Intcode($intcode);

    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        $computer->calc();
    }

    Test::equal('test 2', strlen($computer->getSignal()), 16);

    $intcode = Reader::read('test/day09-test3');
    $computer = new Intcode($intcode);

    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        $computer->calc();
    }

    Test::equal('test 3', $computer->getSignal(), '1125899906842624');

    echo "\n";
}
// test end

$intcode = Reader::read('day09');

$computer = new Intcode($intcode);
$computer->setInputs(1);

while ($computer->getStatus() != Intcode::STATUS_HALT) {
    $computer->calc();
}

echo $computer->getSignal(), "\n";
