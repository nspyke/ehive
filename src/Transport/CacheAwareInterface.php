<?php

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
