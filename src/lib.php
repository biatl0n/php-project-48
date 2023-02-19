<?php

namespace Gendiff;

function getDiff($file1, $file2)
{
    $arr1 = json_decode(file_get_contents($file1), true) or die('Ошибка Json');
    $arr2 = json_decode(file_get_contents($file2), true) or die('Ошибка Json');
    ksort($arr1);
    ksort($arr2);
    $resultString = "{\n";

    foreach ($arr1 as $key => $value) {

        if (array_key_exists($key, $arr2)) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
                $arr2Value = $arr2[$key] ? 'true' : 'false';
            } else {
                $arr2Value = $arr2[$key];
            }

            $resultString .= $arr2Value === $value ? "    {$key}:  {$value} \n" : "  - {$key}:  {$value} \n  + {$key}:  {$arr2Value} \n";
        } else {
            $resultString .= "  - {$key}: {$value} \n";
        }
    }

    foreach ($arr2 as $key => $value) {
        if (!array_key_exists($key, $arr1)) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $resultString .= "  + {$key}: {$value} \n";
        }
    }

    return $resultString .= "}".PHP_EOL;
}

//print_r(getDiff('file1.json', 'file2.json'));