<?php

namespace Differ\Formatters\Json;

function genJson($diff)
{
    return json_encode($diff) . PHP_EOL;
}
