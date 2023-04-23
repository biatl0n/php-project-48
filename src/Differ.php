<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\genFormattedString;
use function Functional\sort;

function readFile(string $filePath): object
{
    if (!file_exists($filePath)) {
        throw new \Exception("The file {$filePath} does not exists.");
    }

    $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
    $fileContent = (string) file_get_contents($filePath);
    return parse($fileContent, $fileType);
}

function genDiff(string $file1Path, string $file2Path, string $format = 'stylish'): string
{
    $file1Content = readFile($file1Path);
    $file2Content = readFile($file2Path);
    $diff = makeDiff($file1Content, $file2Content);
    return genFormattedString($diff, $format);
}

function makeDiff(object $file1Content, object $file2Content): array
{
    $file1Keys = array_keys(get_object_vars($file1Content));
    $file2Keys = array_keys(get_object_vars($file2Content));
    $uniqKeys = array_unique(array_merge($file1Keys, $file2Keys));
    $sortedUniqKeys = sort($uniqKeys, fn($left, $right) => strcmp($left, $right));

    $diffArray = array_map(function ($key) use ($file1Content, $file2Content): array {
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

function makeNode(string $key, string $status, mixed $file1Value, mixed $file2Value, mixed $children = null): array
{
    return [
        'key' => $key,
        'status' => $status,
        'file1Value' => $file1Value,
        'file2Value' => $file2Value,
        'children' => $children
    ];
}
