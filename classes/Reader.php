<?php
class Reader
{
    public static function read($filename)
    {
        $content = file_get_contents(__DIR__ . '/../input/' . $filename . '.txt');
        return trim($content);
    }

    public static function split($demiliter, $filename)
    {
        $content = self::read($filename);
        return explode($demiliter, $content);
    }
}
