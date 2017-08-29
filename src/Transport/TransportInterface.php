<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
