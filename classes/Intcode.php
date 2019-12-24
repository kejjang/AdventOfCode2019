<?php
class Intcode
{
    const STATUS_STOP    = 0;
    const STATUS_RUNNING = 1;
    const STATUS_WAIT    = 2;
    const STATUS_HALT    = 3;

    private $code;          // intcode
    private $status;        // 0: stop, 1: running, 2: wait, 3: halt
    private $signal;        // output signal
    private $pos;           // current opcode position
    private $inputs;        // input signals
    private $relative_base; // relative base

    public function __construct($intcode)
    {
        $this->code = explode(',', $intcode);
        $this->status = self::STATUS_RUNNING;
        $this->signal = false;
        $this->pos = 0;
        $this->inputs = [];
        $this->relative_base = 0;

        return true;
    }

    public function setCode($pos, $code)
    {
        $this->code[$pos] = $code;
        return true;
    }

    public function setInputs($inputs)
    {
        if (is_array($inputs)) {
            $this->inputs = array_merge($this->inputs, $inputs);
        } else {
            $this->inputs[] = $inputs;
        }

        $this->status = self::STATUS_RUNNING;

        return true;
    }

    private function getCodeValue($mode, $pos_index)
    {
        $pos_value = @$this->code[$this->pos + $pos_index];
        $return_value = false;

        switch (intval($mode)) {
            case 1:
                $return_value = $pos_value;
                break;
            case 2:
                $return_value = @$this->code[$this->relative_base + $pos_value];
                break;
            case 0:
            default:
                $return_value = @$this->code[$pos_value];
                break;
        }

        return $return_value;
    }

    private function setCodeValue($mode, $pos_index, $value)
    {
        $pos_value = @$this->code[$this->pos + $pos_index];
        switch (intval($mode)) {
            case 2:
                $this->code[$this->relative_base + $pos_value] = $value;
                break;
            case 0:
            case 1:
            default:
                $this->code[$pos_value] = $value;
                break;
        }
        return true;
    }

    public function calc()
    {
        $inst = sprintf('%05d', $this->code[$this->pos]);

        $op    = substr($inst, -2);
        $mode1 = substr($inst, 2, 1);
        $mode2 = substr($inst, 1, 1);
        $mode3 = substr($inst, 0, 1);

        $jump = false;

        switch ($op) {
            case '99':
                $this->status = self::STATUS_HALT;
                break;
            case '01':
                // $pos1 = $this->code[$this->pos + 1];
                // $pos2 = $this->code[$this->pos + 2];
                // $pos3 = $this->code[$this->pos + 3];

                // $n1 = $mode1 === '0' ? $this->code[$pos1] : $pos1;
                // $n2 = $mode2 === '0' ? $this->code[$pos2] : $pos2;
                $n1 = $this->getCodeValue($mode1, 1);
                $n2 = $this->getCodeValue($mode2, 2);

                $n3 = $n1 + $n2;
                // $this->code[$pos3] = $n3;
                $this->setCodeValue($mode3, 3, $n3);

                $this->pos += 4;
                break;
            case '02':
                // $pos1 = $this->code[$this->pos + 1];
                // $pos2 = $this->code[$this->pos + 2];
                // $pos3 = $this->code[$this->pos + 3];

                // $n1 = $mode1 === '0' ? $this->code[$pos1] : $pos1;
                // $n2 = $mode2 === '0' ? $this->code[$pos2] : $pos2;
                $n1 = $this->getCodeValue($mode1, 1);
                $n2 = $this->getCodeValue($mode2, 2);

                $n3 = $n1 * $n2;
                // $this->code[$pos3] = $n3;
                $this->setCodeValue($mode3, 3, $n3);

                $this->pos += 4;
                break;
            case '03':
                if (count($this->inputs) > 0) {
                    $input = array_shift($this->inputs);
                } else {
                    $input = false;
                }

                if ($input === false) {
                    $this->status = self::STATUS_WAIT;
                } else {
                    // if ($mode1 === '0') {
                    //     $pos1 = $this->code[$this->pos + 1];
                    //     $this->code[$pos1] = $input;
                    // } else {
                    //     $this->code[$this->pos + 1] = $input;
                    // }
                    $this->setCodeValue($mode1, 1, $input);

                    $this->pos += 2;
                }
                break;
            case '04':
                // if ($mode1 === '0') {
                //     $pos1 = $this->code[$this->pos + 1];
                //     $this->signal = $this->code[$pos1];
                // } else {
                //     $this->signal = $this->code[$this->pos + 1];
                // }
                $this->signal = $this->getCodeValue($mode1, 1);

                $this->pos += 2;
                $this->status = self::STATUS_WAIT;
                break;
            case '05':
                // $pos1 = $this->code[$this->pos + 1];
                // $pos2 = $this->code[$this->pos + 2];

                // $n1 = $mode1 === '0' ? $this->code[$pos1] : $pos1;
                // $n2 = $mode2 === '0' ? $this->code[$pos2] : $pos2;
                $n1 = $this->getCodeValue($mode1, 1);
                $n2 = $this->getCodeValue($mode2, 2);

                if ($n1 != '0') {
                    $jump = $n2 - $this->pos;
                } else {
                    $jump = 3;
                }
                $this->pos += $jump;
                break;
            case '06':
                // $pos1 = $this->code[$this->pos + 1];
                // $pos2 = $this->code[$this->pos + 2];

                // $n1 = $mode1 === '0' ? $this->code[$pos1] : $pos1;
                // $n2 = $mode2 === '0' ? $this->code[$pos2] : $pos2;
                $n1 = $this->getCodeValue($mode1, 1);
                $n2 = $this->getCodeValue($mode2, 2);

                if ($n1 == '0') {
                    $jump = $n2 - $this->pos;
                } else {
                    $jump = 3;
                }
                $this->pos += $jump;
                break;
            case '07':
                // $pos1 = $this->code[$this->pos + 1];
                // $pos2 = $this->code[$this->pos + 2];
                // $pos3 = $this->code[$this->pos + 3];

                // $n1 = $mode1 === '0' ? $this->code[$pos1] : $pos1;
                // $n2 = $mode2 === '0' ? $this->code[$pos2] : $pos2;
                $n1 = $this->getCodeValue($mode1, 1);
                $n2 = $this->getCodeValue($mode2, 2);

                if ($n1 < $n2) {
                    // $this->code[$pos3] = 1;
                    $this->setCodeValue($mode3, 3, 1);
                } else {
                    // $this->code[$pos3] = 0;
                    $this->setCodeValue($mode3, 3, 0);
                }
                $this->pos += 4;
                break;
            case '08':
                // $pos1 = $this->code[$this->pos + 1];
                // $pos2 = $this->code[$this->pos + 2];
                // $pos3 = $this->code[$this->pos + 3];

                // $n1 = $mode1 === '0' ? $this->code[$pos1] : $pos1;
                // $n2 = $mode2 === '0' ? $this->code[$pos2] : $pos2;
                $n1 = $this->getCodeValue($mode1, 1);
                $n2 = $this->getCodeValue($mode2, 2);

                if ($n1 == $n2) {
                    // $this->code[$pos3] = 1;
                    $this->setCodeValue($mode3, 3, 1);
                } else {
                    // $this->code[$pos3] = 0;
                    $this->setCodeValue($mode3, 3, 0);
                }
                $this->pos += 4;
                break;
            case '09':
                $n1 = $this->getCodeValue($mode1, 1);
                $this->relative_base += $n1;
                $this->pos += 2;
                break;
        }

        return [$op, $this->status];
    }

    public function getSignal()
    {
        return $this->signal;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCode()
    {
        return implode(',', $this->code);
    }
}
