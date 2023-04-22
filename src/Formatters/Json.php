<?php

namespace Gendiff\Formatters\Json;

function genJson($diff)
{
    return json_encode($diff) . PHP_EOL;
}
