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

namespace Desarrolla2\Cache;

/**
 * CacheInterface
 */
interface CacheInterface
{
    /**
     * Delete a value from the cache
     *
     * @param string $key
     */
    public function delete($key);

    /**
     * Retrieve the value corresponding to a provided key
     *
     * @param string $key
     */
    public function get($key);

    /**
     *
     * @return \Desarrolla2\Cache\Adapter\AdapterInterface $adapter
     * @throws Exception
     */
    public function getAdapter();

    /**
     * Retrieve the if value corresponding to a provided key exist
     *
     * @param string $key
     */
    public function has($key);

    /**
     * * Add a value to the cache under a unique key
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $ttl
     */
    public function set($key, $value, $ttl = null);

    /**
     * Set Adapter interface
     *
     * @param \Desarrolla2\Cache\Adapter\AdapterInterface $adapter
     */
    public function setAdapter(\Desarrolla2\Cache\Adapter\AdapterInterface $adapter);

    /**
     * Set option for Adapter
     *
     * @param string $key
     * @param string $value
     */
    public function setOption($key, $value);

    /**
     * clean all expired records from cache
     */
    public function clearCache();

    /**
     * clear all cache
     */
    public function dropCache();
}
