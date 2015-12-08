<?php

/*
 * This file is part of the Cache package.
 *
 * Copyright (c) Daniel González
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Daniel González <daniel@desarrolla2.com>
 */

namespace Desarrolla2\Cache\Adapter;

use \Memcache as BaseMemCache;

/**
 * MemCache
 */
class MemCache extends AbstractAdapter
{
    /**
     *
     * @var \Memcache
     */
    protected $server;

    public function __construct()
    {
        $this->server = new BaseMemcache();
        $this->server->addServer('localhost', 11211);
    }

    /**
     *
     * @param string $host
     * @param string $port
     */
    public function addServer($host, $port)
    {
        $this->server->addServer($host, $port);
    }

    /**
     * Delete a value from the cache
     *
     * @param string $key
     */
    public function delete($key)
    {
        $tKey = $this->getKey($key);
        $this->server->delete($tKey);
    }

    /**
     * {@inheritdoc }
     */
    public function get($key)
    {
        $tKey = $this->getKey($key);
        $data = $this->server->get($tKey);

        return $this->unserialize($data);
    }

    /**
     * {@inheritdoc }
     */
    public function has($key)
    {
        $tKey = $this->getKey($key);
        if ($this->server->get($tKey)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function set($key, $value, $ttl = null)
    {
        $tKey = $this->getKey($key);
        $tValue = $this->serialize($value);
        if (!$ttl) {
            $ttl = $this->ttl;
        }
        $this->server->set($tKey, $tValue, false, time() + $ttl);
    }

    public function dropCache($delay = 0)
    {
        $this->server->flush($delay);
    }
}
