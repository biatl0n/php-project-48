<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function toString($value)
{
    return trim(var_export($value, true), "'");
}

function getFileType($file)
{
    $substr = mb_substr($file, -5, mb_strlen($file));
    switch ($substr) {
        case mb_strstr($substr, '.yml') === '.yml':
            return 'yml';
        case mb_strstr($substr, '.yaml') === '.yaml':
            return 'yml';
        case mb_strstr($substr, '.json') === '.json':
            return 'json';
        default:
            return '';
    }
}

function readFile($file)
{
    (file_exists($file)) ?: exit("File does not exist" . PHP_EOL);
    $fileContent = file_get_contents($file);
    $ftype = getFileType($file);
    if ($ftype === 'json') {
        $result = json_decode($fileContent);
    } elseif ($ftype === 'yml') {
        $result = Yaml::parse($fileContent, YAML::PARSE_OBJECT_FOR_MAP);
    }
    return $result;
}
