<?php
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $intcode = '109,1,204,-1,1001,100,1,100,1008,100,16,101,1006,101,0,99';
    $computer = new Intcode($intcode);

    $output = [];
    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        [$op, $status] = $computer->calc();
        if($op == '04'){
            $output[] = $computer->getSignal();
        }
    }
    $output = implode(',', $output);

    Test::equal('test 1', $output, $intcode);

    $intcode = '1102,34915192,34915192,7,4,7,99,0';
    $computer = new Intcode($intcode);

    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        $computer->calc();
    }

    Test::equal('test 2', strlen($computer->getSignal()), 16);

    $intcode = '104,1125899906842624,99';
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
