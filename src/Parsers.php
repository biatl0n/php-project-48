<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath, string $fileType)
{
    switch ($fileType) {
        case 'yml':
            return Yaml::parse($filePath, YAML::PARSE_OBJECT_FOR_MAP);
        case 'yaml':
            return Yaml::parse($filePath, YAML::PARSE_OBJECT_FOR_MAP);
        case 'json':
            return json_decode($filePath);
        default:
            return 'Not supported format';
    }
}
