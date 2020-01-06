<?php
include_once __DIR__ . "/autoload.php";

$intcode = Reader::read('day13');

$game = new ArcadeCabinet($intcode);
$game->runIntcode();
echo $game->getTileCount(2), "\n";
