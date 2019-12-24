<?php
include_once __DIR__ . "/autoload.php";

$w = 25;
$h = 6;
$img_code = Reader::read('day08');
$debug = false;

$decoder = new ImageDecoder($w, $h, $img_code, $debug);
$layer = $decoder->getFewest0Layer();

echo $layer[1] * $layer[2], "\n";
