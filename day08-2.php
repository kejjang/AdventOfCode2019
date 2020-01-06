<?php
include_once __DIR__ . "/autoload.php";

$run_test = true;

// test start
if ($run_test) {
    $w = 2;
    $h = 2;
    $img_code = Reader::read('test/day08-test1');
    $debug = false;

    $decoder = new ImageDecoder($w, $h, $img_code, $debug);

    $test_output = $decoder->drawPic(true, true);
    Test::equal('test 1', $test_output, "01\n10");
}
// test end

$w = 25;
$h = 6;
$img_code = Reader::read('day08');
$debug = false;

$decoder = new ImageDecoder($w, $h, $img_code, $debug);
$decoder->drawPic();
