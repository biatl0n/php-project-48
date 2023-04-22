<?php

namespace Gendiff\Formatters;

use function Gendiff\Formatters\Plain\genPlain;
use function Gendiff\Formatters\Stylish\genStylish;

function genFormattedString($diff, $format)
{
    switch ($format) {
        case 'stylish':
            return genStylish($diff);
        case 'plain':
            return genPlain($diff);
        default:
            return "Unknown format" . PHP_EOL;
    }
}