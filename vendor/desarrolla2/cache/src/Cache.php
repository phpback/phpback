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

use Desarrolla2\Cache\Adapter\AdapterInterface;
use Desarrolla2\Cache\Exception\AdapterNotSetException;

/**
 * Cache
 */
class Cache implements CacheInterface
{
    /**
     *
     * @var Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        if ($adapter) {
            $this->setAdapter($adapter);
        }
    }

    /**
     * {@inheritdoc }
     *
     * @param string $key
     */
    public function delete($key)
    {
        $this->getAdapter()->delete($key);
    }

    /**
     * {@inheritdoc }
     *
     * @param string $key
     */
    public function get($key)
    {
        return $this->getAdapter()->get($key);
    }

    /**
     * {@inheritdoc }
     */
    public function getAdapter()
    {
        if (!$this->adapter) {
            throw new AdapterNotSetException('Required Adapter');
        }

        return $this->adapter;
    }

    /**
     * {@inheritdoc }
     *
     * @param string $key
     */
    public function has($key)
    {
        return $this->getAdapter()->has($key);
    }

    /**
     * {@inheritdoc }
     *
     * @param string $key
     * @param mixed  $value
     * @param null   $ttl
     */
    public function set($key, $value, $ttl = null)
    {
        $this->getAdapter()->set($key, $value, $ttl);
    }

    /**
     * {@inheritdoc }
     *
     * @param Adapter\AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc }
     *
     * @param  string $key
     * @param  string $value
     * @return mixed
     */
    public function setOption($key, $value)
    {
        return $this->adapter->setOption($key, $value);
    }

    /**
     * {@inheritdoc }
     */
    public function clearCache()
    {
        $this->adapter->clearCache();
    }

    /**
     * {@inheritdoc }
     */
    public function dropCache()
    {
        $this->adapter->dropCache();
    }
}
