<?php

namespace EHive\Transport;

interface TransportInterface
{
    /**
     * @param string $path
     * @param string $queryString
     *
     * @return array|string
     */
    public function get($path, $queryString = '');

    /**
     * @param string $path
     * @param string $content
     *
     * @return array|string
     */
    public function post($path, $content = '');

    /**
     * @param string $path
     * @param string $content
     *
     * @return array|string
     */
    public function put($path, $content = '');

    /**
     * @param string $path
     * @param string $queryString
     *
     * @return array|string
     */
    public function delete($path, $queryString = '');
}
