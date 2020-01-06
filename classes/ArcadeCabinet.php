<?php

use Intcode as Intcode;

class ArcadeCabinet
{
    private $computer;
    private $draw_data;

    public function __construct($intcode = false)
    {
        $this->draw_data = [];
        if ($intcode !== false) {
            $this->initComputer($intcode);
        }
        return true;
    }

    public function initComputer($intcode = false)
    {
        if ($intcode !== false) {
            $this->computer = new Intcode($intcode);
        }
        return true;
    }

    public function setInputs($inputs)
    {
        return $this->computer->setInputs($inputs);
    }

    public function runIntcode()
    {
        $draw_data = [];

        while ($this->computer->getStatus() != Intcode::STATUS_HALT) {
            list($op, $status) = $this->computer->calc();
            if ($op == '04') {
                $output = $this->computer->getSignal();
                // echo $output, ', ';
                $draw_data[] = $output;
                if(in_array($output, [0, -1, 1])){
                    $this->setInputs($output);
                }
            }
        }

        $this->draw_data = array_chunk($draw_data, 3);

        return true;
    }

    public function getDrawData()
    {
        return $this->draw_data;
    }

    public function getTileCount($tile_type)
    {
        $count = 0;

        foreach ($this->draw_data as $part) {
            if ($part[2] == $tile_type) {
                $count++;
            }
        }

        return $count;
    }
}
