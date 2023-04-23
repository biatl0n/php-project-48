<?php

namespace Differ\Formatters\Json;

function genJson(array $diff)
{
    return json_encode($diff);
}
