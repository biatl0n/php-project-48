<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($file)
{
    $fileContent = file_get_contents($file);
    $substr = mb_substr($file, -5, mb_strlen($file));
    switch ($substr) {
        case mb_strstr($substr, '.yml') === '.yml':
            return Yaml::parse($fileContent, YAML::PARSE_OBJECT_FOR_MAP);
        case mb_strstr($substr, '.yaml') === '.yaml':
            return Yaml::parse($fileContent, YAML::PARSE_OBJECT_FOR_MAP);
        case mb_strstr($substr, '.json') === '.json':
            return json_decode($fileContent);
        default:
            return 'Not supported format';
    }
}
