<?php


namespace EHive\Transport;

interface TransportInterface
{
    public function get($path, $queryString = '', $useCache = false);

    public function post($path, $content = '');

    public function put($path, $content = '');

    public function delete($path, $queryString = '');
}
