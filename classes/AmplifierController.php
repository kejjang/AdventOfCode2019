<?php

use Intcode as Amplifier;

class AmplifierController
{
    private $amps = [];
    private $possible_phases = [];
    private $signals = [];
    private $intcode;

    public function __construct($intcode = false, $possible_phases = false)
    {
        if ($intcode !== false) {
            $this->setIntCode($intcode);
        }
        if ($possible_phases !== false) {
            $this->setPossiblePhases($possible_phases);
        }
        return true;
    }

    public function setIntCode($intcode)
    {
        $this->intcode = $intcode;
        return true;
    }

    public function setPossiblePhases($possible_phases)
    {
        $this->possible_phases = $possible_phases;
        return true;
    }

    public function run()
    {
        $this->signals = [];

        foreach ($this->possible_phases as $test_phases) {
            $this->amps = [];
            for ($i = 0; $i < 5; $i++) {
                $this->amps[] = new Amplifier($this->intcode);
            }
            // $signals[] = calc_signal_test($amps, $test_phases, $phase_mode);
            $this->signals[] = $this->calc_signal($this->amps, $test_phases);
        }

        return max($this->signals);
    }

    private function calc_signal($amps, $phases)
    {
        $signal = 0;

        foreach ($amps as $idx => $amp) {
            $phase = $phases[$idx];
            $amp->setInputs($phase);
        }

        $amp_idx = 0;
        $status = $amps[$amp_idx]->getStatus();

        while (!($status == 3 && $amp_idx == 0)) {
            $amps[$amp_idx]->setInputs($signal);
            $status = $amps[$amp_idx]->getStatus();

            while ($status == 1) {
                [$op, $status] = $amps[$amp_idx]->calc();
                // echo "amp: ", $amp_idx, ", op: ", $op, ", status: ", $status, ", signal: ", $amps[$amp_idx]->getSignal(), "\n";
            }

            $signal = $amps[$amp_idx]->getSignal();
            $amp_idx = ($amp_idx + 1) % 5;
        }

        return $signal;
    }

    private function calc_signal_test($amps, $phases, $mode)
    {
        if ($mode == PossiblePhaseGenerator::MODE_GENERAL) {
            $signal = 0;
            foreach ($amps as $idx => $amp) {
                $phase = $phases[$idx];
                $amp->setInputs([$phase, $signal]);
                $status = $amp->getStatus();
                while ($status == 1) {
                    [$op, $status] = $amp->calc();
                }
                $signal = $amp->getSignal();
            }
        } elseif ($mode == PossiblePhaseGenerator::MODE_FEEDBACK) {
            $signal = 0;

            foreach ($amps as $idx => $amp) {
                $phase = $phases[$idx];
                $amp->setInputs($phase);
            }

            $amp_idx = 0;
            $status = $amps[$amp_idx]->getStatus();

            while (!($status == 3 && $amp_idx == 0)) {
                $amps[$amp_idx]->setInputs($signal);
                $status = $amps[$amp_idx]->getStatus();

                while ($status == 1) {
                    [$op, $status] = $amps[$amp_idx]->calc();
                    // echo "amp: ", $amp_idx, ", op: ", $op, ", status: ", $status, ", signal: ", $amps[$amp_idx]->getSignal(), "\n";
                }

                $signal = $amps[$amp_idx]->getSignal();
                $amp_idx = ($amp_idx + 1) % 5;
            }
        }

        return $signal;
    }
}
