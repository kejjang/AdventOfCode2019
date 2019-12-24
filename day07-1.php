<?php
include_once __DIR__ . "/autoload.php";

use PhaseUtility as PossiblePhaseGenerator;

$run_test = true;

// test start
if ($run_test) {
    $intcode = '3,15,3,16,1002,16,10,16,1,16,15,15,4,15,99,0,0';
    // $phase_mode = PossiblePhaseGenerator::MODE_GENERAL;
    $possible_phases = [[4, 3, 2, 1, 0]];
    $amp_ctrl = new AmplifierController($intcode, $possible_phases);
    $max_signal = $amp_ctrl->run();
    Test::equal('test 1', $max_signal, 43210);

    $intcode = '3,23,3,24,1002,24,10,24,1002,23,-1,23,101,5,23,23,1,24,23,23,4,23,99,0,0';
    // $phase_mode = PossiblePhaseGenerator::MODE_GENERAL;
    $possible_phases = [[0, 1, 2, 3, 4]];
    $amp_ctrl = new AmplifierController($intcode, $possible_phases);
    $max_signal = $amp_ctrl->run();
    Test::equal('test 2', $max_signal, 54321);

    $intcode = '3,31,3,32,1002,32,10,32,1001,31,-2,31,1007,31,0,33,1002,33,7,33,1,33,31,31,1,32,31,31,4,31,99,0,0,0';
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
