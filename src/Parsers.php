<?php

namespace Gendiff\Parsers;

use ArrayObject;
use Symfony\Component\Yaml\Yaml;

function bool2str(&$arr)
{
    foreach ($arr as $key => $val) {
        if (is_bool($val)) {
            $val ? $arr->offsetSet($key, 'true') : $arr->offsetSet($key, 'false');
        }
    }
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
        $result = new ArrayObject(json_decode($fileContent));
    } elseif ($ftype === 'yml') {
        $result = new ArrayObject(Yaml::parse($fileContent, YAML::PARSE_OBJECT_FOR_MAP));
    }
    $result->ksort();
    bool2str($result);
    return $result;
}
