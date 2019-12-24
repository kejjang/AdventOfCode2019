<?php
include_once __DIR__ . "/autoload.php";

$ranges = Reader::split("\n", 'day04');

echo PasswdUtility::guessCount($ranges, true), "\n";
