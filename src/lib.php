<?php

namespace Gendiff\Lib;

use function Gendiff\Parsers\readFiles;

function gendiff($file1, $file2)
{
    [$arr1, $arr2] = readFiles($file1, $file2);
    $resultString = "{\n";
    foreach ($arr1 as $key => $val) {
        if ($arr2->offsetExists($key)) {
            $resultString .= $arr2->offsetGet($key) === $val ? "    {$key}: {$val} \n" :
                "  - {$key}: {$val} \n  + {$key}: {$arr2->offsetGet($key)} \n";
        } else {
            $resultString .= "  - {$key}: {$val} \n";
        }
    }
    foreach ($arr2 as $key => $val) {
        if (!$arr1->offsetExists($key)) {
            $resultString .= "  + {$key}: {$val} \n";
        }
    }
    return $resultString . "}" . PHP_EOL;
}
