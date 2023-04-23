<?php

namespace Differ\Formatters\Plain;

use function Functional\flatten;

function genPlain(array $diff)
{
    $iter = function ($diff, $path = []) use (&$iter): mixed {
        return array_map(function ($node) use ($iter, $path) {
            [
                'key' => $key,
                'status' => $status,
                'file1Value' => $file1Value,
                'file2Value' => $file2Value,
                'children' => $children
            ] = $node;

            $propertyPath = implode('.', [...$path, $key]);

            switch ($status) {
                case 'nested':
                    return ($iter($children, [...$path, $key]));
                case 'added':
                    $value = buildValue($file2Value);
                    return "Property '{$propertyPath}' was added with value: {$value}";
                case 'removed':
                    $value = buildValue($file1Value);
                    return "Property '{$propertyPath}' was removed";
                case 'actual':
                    return [];
                case 'changed':
                    $value1 = is_object($file1Value) ? "[complex value]" : buildValue($file1Value);
                    $value2 = is_object($file2Value) ? "[complex value]" : buildValue($file2Value);
                    return "Property '{$propertyPath}' was updated. From {$value1} to {$value2}";
            }
        }, $diff);
    };
    $string = implode("\n", flatten($iter($diff)));
    return "$string";
}

function buildValue(mixed $value)
{
    if (is_bool($value) || is_null($value)) {
        return strtolower(trim(var_export($value, true), "'"));
    } elseif (is_object($value)) {
        return "[complex value]";
    } elseif (is_string($value)) {
        return "'{$value}'";
    }
    return (string) $value;
}
