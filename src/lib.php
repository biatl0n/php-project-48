<?php

namespace Gendiff\Lib;

use function Gendiff\Parsers\readFile;
use function Gendiff\Parsers\toString;

function gendiff($file1, $file2)
{
    $diff = [];
    $file1Content = readFile($file1);
    $file2Content = readFile($file2);

    foreach ($file1Content as $key => $val) {
        $strVal = toString($val);
        if (isset($file2Content->$key)) {
            if ($file2Content->$key === $val) {
                $diff[$key] = ['left' => "{$strVal}", 'right' => "{$strVal}", 'actual' => true];
            } else {
                $strVal2 = toString($file2Content->$key);
                $diff[$key] = ['left' => "{$strVal}", 'right' => "{$strVal2}", 'actual' => 'changed'];
            }
        } else {
            $diff[$key] = ['left' => "{$strVal}", 'right' => null, 'actual' => 'removed'];
        }
    }

    foreach ($file2Content as $key => $val) {
        $strVal = toString($val);
        if (!isset($file1Content->$key)) {
            $diff[$key] = ['left' => null, 'right' => "{$strVal}", 'actual' => 'new'];
        }
    }

    ksort($diff);
    $result = '';
    foreach ($diff as $key => $val) {
        $indent = '    ';
        $indentPlus = '  + ';
        $indentMinus = '  - ';
        if ($val['actual'] === true) {
            $result .= "{$indent}{$key}: {$val['left']}\n";
        } elseif ($val['actual'] === 'changed') {
            $result .= "{$indentMinus}{$key}: {$val['left']}\n";
            $result .= "{$indentPlus}{$key}: {$val['right']}\n";
        } elseif ($val['actual'] === 'removed') {
            $result .= "{$indentMinus}{$key}: {$val['left']}\n";
        } elseif ($val['actual'] === 'new') {
            $result .= "{$indentPlus}{$key}: {$val['right']}\n";
        }
    }
    return "{\n{$result}}" . PHP_EOL;
}