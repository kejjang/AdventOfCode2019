<?php
include_once __DIR__ . "/autoload.php";

$intcode = Reader::read('day13');
$intcode = '2' . substr($intcode, 1);

$game = new ArcadeCabinet($intcode);
$game->runIntcode();
// echo implode(',', $game->getDrawData()), "\n";
