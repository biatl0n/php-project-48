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

function readFiles($file1, $file2)
{
    (file_exists($file1)) ?: exit("File 1 does not exist" . PHP_EOL);
    (file_exists($file2)) ?: exit("File 2 does not exist" . PHP_EOL);

    $string1 = file_get_contents($file1);
    $string2 = file_get_contents($file2);
    $ftype = getFileType($file1);
    if ($ftype !== getFileType($file2)) {
        exit("Formats do not match" . PHP_EOL);
    }

    if ($ftype === 'json') {
        $arr1 = new ArrayObject(json_decode($string1));
        $arr2 = new ArrayObject(json_decode($string2));
    } elseif ($ftype === 'yml') {
        $arr1 = new ArrayObject(Yaml::parse($string1, YAML::PARSE_OBJECT_FOR_MAP));
        $arr2 = new ArrayObject(Yaml::parse($string2, YAML::PARSE_OBJECT_FOR_MAP));
    }

    $arr1->ksort();
    $arr2->ksort();
    bool2str($arr1);
    bool2str($arr2);

    return [$arr1, $arr2];
}
