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

use Psr\SimpleCache\CacheInterface;

interface CacheAwareInterface
{
    /**
     * @param CacheInterface $cache
     *
     * @return $this
     */
    public function setCache(CacheInterface $cache);

    /**
     * @param int $ttl
     *
     * @return $this
     */
    public function setCacheTtl($ttl);

    /**
     * @param bool $bool
     *
     * @return $this
     */
    public function setCacheEnabled($bool);
}
