<?php
include_once __DIR__ . "/autoload.php";

$intcode = Reader::read('day02');

for ($noun = 0; $noun < 100; $noun++) {
    for ($verb = 0; $verb < 100; $verb++) {
        $computer = new Intcode($intcode);
        $computer->setCode(1, $noun);
        $computer->setCode(2, $verb);

        while ($computer->getStatus() != Intcode::STATUS_HALT) {
            $computer->calc();
        }
        $intcode_calc = explode(',', $computer->getCode());

        if ($intcode_calc[0] == 19690720) {
            echo 'before: ', $intcode, "\n";
            echo 'after:  ', implode(',', $intcode_calc), "\n";
            echo 'noun: ', $noun, "\n";
            echo 'verb: ', $verb, "\n";
            echo 'answer: ', 100 * $noun + $verb, "\n";
        }
    }
}
