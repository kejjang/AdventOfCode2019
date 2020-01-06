<?php
include_once __DIR__ . "/autoload.php";

use PhaseUtility as PossiblePhaseGenerator;

$run_test = true;

// test start
if ($run_test) {
    $intcode = Reader::read('test/day07-test4');
    // $phase_mode = PossiblePhaseGenerator::MODE_FEEDBACK;
    $possible_phases = [[9, 8, 7, 6, 5]];
    $amp_ctrl = new AmplifierController($intcode, $possible_phases);
    $max_signal = $amp_ctrl->run();
    Test::equal('test 4', $max_signal, 139629729);

    $intcode = Reader::read('test/day07-test5');
    // $phase_mode = PossiblePhaseGenerator::MODE_FEEDBACK;
    $possible_phases = [[9, 7, 8, 5, 6]];
    $amp_ctrl = new AmplifierController($intcode, $possible_phases);
    $max_signal = $amp_ctrl->run();
    Test::equal('test 5', $max_signal, 18216);

    echo "\n";
}
// test end

$intcode = Reader::read('day07');

$phase_mode = PossiblePhaseGenerator::MODE_FEEDBACK;
$possible_phases = PossiblePhaseGenerator::gen($phase_mode);

$amp_ctrl = new AmplifierController($intcode, $possible_phases);
$max_signal = $amp_ctrl->run();

echo "max signal: ", $max_signal, "\n";
