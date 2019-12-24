<?php
class ImageDecoder
{
    private $debug = false;
    private $width = false;
    private $height = false;
    private $img_code = false;
    private $layers = [];
    private $pic = [];
    private $pic_pos = 0;


    public function __construct($width = false, $height = false, $img_code = false, $debug = false)
    {
        if ($width !== false) {
            $this->setWidth($width);
        }
        if ($height !== false) {
            $this->setHeight($height);
        }
        if ($img_code !== false) {
            $this->setImgCode($img_code);
        }

        $this->setDebug($debug);

        if ($this->checkIsValidInputs()) {
            $this->setImageLayers();
        }

        return true;
    }

    public function setWidth($width)
    {
        $this->width = $width;

        if ($this->checkIsValidInputs()) {
            $this->setImageLayers();
        }
        return true;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        if ($this->checkIsValidInputs()) {
            $this->setImageLayers();
        }
        return true;
    }

    public function setImgCode($img_code)
    {
        $this->img_code = $img_code;

        if ($this->checkIsValidInputs()) {
            $this->setImageLayers();
        }
        return true;
    }


    public function setDebug($debug = false)
    {
        $this->debug = $debug;
        return true;
    }

    public function checkIsValidInputs()
    {
        if ($this->width !== false && $this->height !== false && $this->img_code !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function setImageLayers()
    {
        $layer = 0;
        $this->layers = [];
        $this->pic = [];
        $this->pic_pos = 0;

        $img_code = preg_split('//', $this->img_code, -1, PREG_SPLIT_NO_EMPTY);
        $debug_info = '';

        for ($pos = 0; $pos < count($img_code); $pos++) {
            if ($pos > 0 && $pos % $this->width == 0) {
                $debug_info .= "\n";
            }
            if ($pos % ($this->width * $this->height) == 0) {
                if ($pos > 0) {
                    $layer++;
                }
                $debug_info .= "\nLayer $layer:\n";
                $this->layers[$layer] = [0, 0, 0];
            }

            $this->layers[$layer][intval($img_code[$pos])]++;
            $debug_info .= $img_code[$pos];

            $this->pic_pos = $pos % ($this->width * $this->height);
            if ($img_code[$pos] != 2 && !isset($this->pic[$this->pic_pos])) {
                $this->pic[$this->pic_pos] = $img_code[$pos];
            }
        }

        $debug_info .= "\n";

        if ($this->debug) {
            echo $debug_info;
        }
    }

    private static function sortByFewest0($a, $b)
    {
        if ($a[0] == $b[0]) {
            return 0;
        } else {
            return $a[0] < $b[0] ? -1 : 1;
        }
    }

    public function getFewest0Layer()
    {
        usort($this->layers, array('ImageDecoder', 'sortByFewest0'));
        return $this->layers[0];
    }

    private function setPixel($pixel, $raw = false)
    {
        return ($raw) ? $pixel : ($pixel == 1 ? "\033[47m \033[0m" : ' ');
    }

    public function drawPic($return = false, $raw = false)
    {
        $output = '';
        $output .= "\n";
        for ($pos = 0; $pos < count($this->pic); $pos++) {
            if ($pos > 0 && $pos % $this->width == 0) {
                $output .= "\n";
            }
            $output .= $this->setPixel($this->pic[$pos], $raw);
        }
        $output .= "\n";

        if ($return) {
            return trim($output);
        } else {
            echo $output;
        }
    }
}
