<?php
include_once __DIR__ . "/autoload.php";

$run_test = false;

// test start
if ($run_test) {
    $map = explode("\n", ".#..#\n.....\n#####\n....#\n...##");
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 1', $detector->getBestLocation(), '3,4');

    $map = explode("\n", "......#.#.\n#..#.#....\n..#######.\n.#.#.###..\n.#..#.....\n..#....#.#\n#..#....#.\n.##.#..###\n##...#..#.\n.#....####");
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 2', $detector->getBestLocation(), '5,8');

    $map = explode("\n", "#.#...#.#.\n.###....#.\n.#....#...\n##.#.#.#.#\n....#.#.#.\n.##..###.#\n..#...##..\n..##....##\n......#...\n.####.###.");
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 3', $detector->getBestLocation(), '1,2');

    $map = explode("\n", ".#..#..###\n####.###.#\n....###.#.\n..###.##.#\n##.##.#.#.\n....###..#\n..#.#..#.#\n#..#.#.###\n.##...##.#\n.....#.#..");
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 4', $detector->getBestLocation(), '6,3');

    $map = explode("\n", ".#..##.###...#######\n##.############..##.\n.#.######.########.#\n.###.#######.####.#.\n#####.##.#.##.###.##\n..#####..#.#########\n####################\n#.####....###.#.#.##\n##.#################\n#####.##.###..####..\n..######..##.#######\n####.##.####...##..#\n.#####..#.######.###\n##...#.##########...\n#.##########.#######\n.####.#.###.###.#.##\n....##.##.###..#####\n.#.#.###########.###\n#.#.#.#####.####.###\n###.##.####.##.#..##");
    $detector = new AsteroidDetector($map);
    $detector->calcObservableCount();
    Test::equal('test 5', $detector->getBestLocation(), '11,13');

    echo "\n";
}
// test end

$map = Reader::split("\n", 'day10');

$detector = new AsteroidDetector($map);
$detector->calcObservableCount();

echo $detector->getBestLocation()['count'], "\n";
