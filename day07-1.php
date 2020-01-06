<?php
include_once __DIR__ . "/autoload.php";

use PhaseUtility as PossiblePhaseGenerator;

$run_test = true;

// test start
if ($run_test) {
    $intcode = Reader::read('test/day07-test1');
    // $phase_mode = PossiblePhaseGenerator::MODE_GENERAL;
    $possible_phases = [[4, 3, 2, 1, 0]];
    $amp_ctrl = new AmplifierController($intcode, $possible_phases);
    $max_signal = $amp_ctrl->run();
    Test::equal('test 1', $max_signal, 43210);

    $intcode = Reader::read('test/day07-test2');
    // $phase_mode = PossiblePhaseGenerator::MODE_GENERAL;
    $possible_phases = [[0, 1, 2, 3, 4]];
    $amp_ctrl = new AmplifierController($intcode, $possible_phases);
    $max_signal = $amp_ctrl->run();
    Test::equal('test 2', $max_signal, 54321);

    $intcode = Reader::read('test/day07-test3');
    // $phase_mode = PossiblePhaseGenerator::MODE_GENERAL;
    $possible_phases = [[1, 0, 4, 3, 2]];
    $amp_ctrl = new AmplifierController($intcode, $possible_phases);
    $max_signal = $amp_ctrl->run();
    Test::equal('test 3', $max_signal, 65210);

    echo "\n";
}
// test end

$intcode = Reader::read('day07');

$phase_mode = PossiblePhaseGenerator::MODE_GENERAL;
$possible_phases = PossiblePhaseGenerator::gen($phase_mode);

$amp_ctrl = new AmplifierController($intcode, $possible_phases);
$max_signal = $amp_ctrl->run();

echo "max signal: ", $max_signal, "\n";
