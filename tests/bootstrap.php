<?php

require_once __DIR__.'/../vendor/autoload.php';

function o(array $json)
{
    return json_decode(json_encode($json));
}
