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

/**
 * NotCache
 */
class NotCache extends AbstractAdapter
{
    /**
     * Delete a value from the cache
     *
     * @param string $key
     */
    public function delete($key)
    {
    }

    /**
     * {@inheritdoc }
     */
    public function get($key)
    {
        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function has($key)
    {
        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function set($key, $value, $ttl = null)
    {
        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function setOption($key, $value)
    {
        return false;
    }
}
