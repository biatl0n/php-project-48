<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\genPlain;
use function Differ\Formatters\Stylish\genStylish;
use function Differ\Formatters\Json\genJson;

function genFormattedString(array $diff, string $format)
{
    switch ($format) {
        case 'stylish':
            return genStylish($diff);
        case 'plain':
            return genPlain($diff);
        case 'json':
            return genJson($diff);
        default:
            return "Unknown format" . PHP_EOL;
    }
}
