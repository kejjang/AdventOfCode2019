<?php
include_once __DIR__ . "/autoload.php";

$run_test = false;

// test start
if ($run_test) {
    $intcode = '1,9,10,3,2,3,11,0,99,30,40,50'; // 3500,9,10,70,2,3,11,0,99,30,40,50
    $computer = new Intcode($intcode);
    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        $computer->calc();
    }
    Test::equal('test 1', explode(',', $computer->getCode())[0], 3500);

    $intcode = '1,0,0,0,99'; // 2,0,0,0,99
    $computer = new Intcode($intcode);
    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        $computer->calc();
    }
    Test::equal('test 2', explode(',', $computer->getCode())[0], 2);

    $intcode = '2,3,0,3,99'; // 2,3,0,6,99
    $computer = new Intcode($intcode);
    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        $computer->calc();
    }
    Test::equal('test 3', explode(',', $computer->getCode())[0], 2);

    $intcode = '2,4,4,5,99,0'; // 2,4,4,5,99,9801
    $computer = new Intcode($intcode);
    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        $computer->calc();
    }
    Test::equal('test 4', explode(',', $computer->getCode())[0], 2);

    $intcode = '1,1,1,4,99,5,6,0,99'; // 30,1,1,4,2,5,6,0,99
    $computer = new Intcode($intcode);
    while ($computer->getStatus() != Intcode::STATUS_HALT) {
        $computer->calc();
    }
    Test::equal('test 5', explode(',', $computer->getCode())[0], 30);

    echo "\n";
}
// test end

$intcode = Reader::read('day02');

$computer = new Intcode($intcode);
echo 'before: ', $computer->getCode(), "\n\n";

$computer->setCode(1, '12');
$computer->setCode(2, '2');
echo '1202 exchage: ', $computer->getCode(), "\n\n";

while ($computer->getStatus() != Intcode::STATUS_HALT) {
    $computer->calc();
}
echo 'after: ', $computer->getCode(), "\n";
echo "\nanswer: ", explode(',', $computer->getCode())[0], "\n";
