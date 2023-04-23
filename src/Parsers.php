<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $file)
{
    $fileContent = file_get_contents($file);
    $type = pathinfo($file, PATHINFO_EXTENSION);
    switch ($type) {
        case 'yml':
            return Yaml::parse($fileContent, YAML::PARSE_OBJECT_FOR_MAP);
        case 'yaml':
            return Yaml::parse($fileContent, YAML::PARSE_OBJECT_FOR_MAP);
        case 'json':
            return json_decode($fileContent);
        default:
            return 'Not supported format';
    }
}
