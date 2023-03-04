<?php

namespace Gendiff\Lib;

function bool2str(&$arr)
{
    array_walk($arr, function (&$val) {
        if (is_bool($val)) {
            $val = $val ? 'true' : 'false';
        }
    });
    return $arr;
}

function readFiles($file1, $file2)
{
    $string1 = file_get_contents($file1);
    $string2 = file_get_contents($file2);
    $arr1 = json_decode($string1, true);
    $arr2 = json_decode($string2, true);
    ksort($arr1);
    ksort($arr2);
    bool2str($arr1);
    bool2str($arr2);
    return [$arr1, $arr2];
}

function gendiff($file1, $file2)
{
    [$arr1, $arr2] = readFiles($file1, $file2);
    $resultString = "{\n";

    array_walk($arr1, function ($val, $key) use (&$resultString, $arr2) {
        if (array_key_exists($key, $arr2)) {
            $resultString .= $arr2[$key] === $val ? "    {$key}:  {$val} \n" :
                "  - {$key}:  {$val} \n  + {$key}:  {$arr2[$key]} \n";
        } else {
            $resultString .= "  - {$key}: {$val} \n";
        }
    });

    array_walk($arr2, function ($val, $key) use (&$resultString, $arr1) {
        if (!array_key_exists($key, $arr1)) {
            $resultString .= "  + {$key}: {$val} \n";
        }
    });

    return $resultString . "}" . PHP_EOL;
}
