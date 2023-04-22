<?php

namespace Gendiff\Differ;

use function Gendiff\Parsers\parse;
use function Gendiff\Formatters\genFormattedString;
use function Functional\sort;

function toString($value)
{
    return trim(var_export($value, true), "'");
}

function readFile($file)
{
    if (!file_exists($file)) {
        print_r("File {$file} does not exist" . PHP_EOL);
        exit;
    } else {
        return parse($file);
    }
}

function genDiff(string $file1Path, string $file2Path, $format = 'stylish')
{
    $file1Content = readFile($file1Path);
    $file2Content = readFile($file2Path);
    $diff = makeDiff($file1Content, $file2Content);
    return genFormattedString($diff, $format);
}

function makeDiff($file1Content, $file2Content)
{
    $file1Keys = array_keys(get_object_vars($file1Content));
    $file2Keys = array_keys(get_object_vars($file2Content));
    $uniqKeys = array_unique(array_merge($file1Keys, $file2Keys));
    $sortedUniqKeys = sort($uniqKeys, fn($left, $right) => strcmp($left, $right));

    $diffArray = array_map(function ($key) use ($file1Content, $file2Content) {
        if (!property_exists($file1Content, $key)) {
            return makeNode($key, 'added', null, $file2Content->$key);
        } elseif (!property_exists($file2Content, $key)) {
            return makeNode($key, 'removed', $file1Content->$key, null);
        } elseif (is_object($file1Content->$key) && is_object($file2Content->$key)) {
            return makeNode($key, 'nested', null, null, makeDiff($file1Content->$key, $file2Content->$key));
        } elseif ($file1Content->$key === $file2Content->$key) {
            return makeNode($key, 'actual', $file1Content->$key, $file2Content->$key);
        }
        return makeNode($key, 'changed', $file1Content->$key, $file2Content->$key);
    }, $sortedUniqKeys);
    return $diffArray;
}

function makeNode(string $key, string $status, $file1Value, $file2Value, $children = null): array
{
    return [
        'key' => $key,
        'status' => $status,
        'file1Value' => $file1Value,
        'file2Value' => $file2Value,
        'children' => $children
    ];
}
