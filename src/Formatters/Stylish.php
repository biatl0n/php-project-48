<?php

namespace Differ\Formatters\Stylish;

use function Functional\flatten;

function genStylish(array $diff)
{
    $iter = function ($diff, $depth) use (&$iter) {
        return array_map(function ($node) use ($depth, $iter) {
            [
                'key' => $key,
                'status' => $status,
                'file1Value' => $file1Value,
                'file2Value' => $file2Value,
                'children' => $children
            ] = $node;

            $indent = buildIndent($depth - 1);

            switch ($status) {
                case 'nested':
                    $indentAfter = buildIndent($depth);
                    return (["{$indent}    {$key}: {", $iter($children, $depth + 1), "{$indentAfter}}"]);
                case 'added':
                    $value = buildValue($file2Value, $depth);
                    return "{$indent}  + {$key}: {$value}";
                case 'removed':
                    $value = buildValue($file1Value, $depth);
                    return "{$indent}  - {$key}: {$value}";
                case 'changed':
                    $value1 = buildValue($file1Value, $depth);
                    $value2 = buildValue($file2Value, $depth);
                    return "{$indent}  - {$key}: {$value1}\n{$indent}  + {$key}: {$value2}";
                case 'actual':
                    $value = buildValue($file1Value, $depth);
                    return "{$indent}    {$key}: {$value}";
            }
        }, $diff);
    };
    $string = implode("\n", flatten($iter($diff, 1)));
    return "{\n$string\n}";
}


function buildIndent(int $depth): string
{
    return str_repeat(" ", 4 * $depth);
}

function buildValue(mixed $value, int $depth)
{
    if (is_bool($value) || is_null($value)) {
        return strtolower(trim(var_export($value, true), "'"));
    }

    if (!is_object($value)) {
        return $value;
    }

    $indent = buildIndent($depth);
    $keys = array_keys(get_object_vars($value));

    $valuesArray = array_map(function ($key) use ($value, $depth, $indent) {
        $subValue = buildValue($value->$key, $depth + 1);
        return "{$indent}    {$key}: {$subValue}";
    }, $keys);

    $valuesString = implode("\n", $valuesArray);
    return "{\n{$valuesString}\n{$indent}}";
}
