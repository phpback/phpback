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

use \Memcached as BaseMemcached;

/**
 * Memcached
 *
 * @author : RJ Garcia <rj@bighead.net>
 */
class Memcached extends AbstractAdapter
{
    /**
     *
     * @var \Memcached
     */
    public $adapter;

    /**
     * Accept three types of inputs: a \Memcached instance already to use
     * as the internal adapter, an array of servers which will be added to
     * a memcached instance, or null, and we'll just build a default memcached
     * instance
     *
     * @param mixed $data
     *
     */
    public function __construct($data = null, $options = array())
    {
        if ($data instanceof BaseMemcached) {
            $this->adapter = $data;
        } else {
            $this->adapter = new BaseMemcached();
        }

        if (is_array($data)) {
            /* if array, then the user supplied an array of servers */
            foreach ($data as $s) {
                $this->adapter->addServer($s['host'], $s['port'], $s['weight']);
            }
        }

        $this->setOption($options);
    }

    /**
     *
     * @param string $host
     * @param int    $port
     * @param int    $weight
     *
     */
    public function addServer($host, $port, $weight = 0)
    {
        $this->adapter->addServer($host, $port, $weight);
    }

    /**
     * Delete a value from the cache
     *
     * @param string $key
     */
    public function delete($key)
    {
        $this->adapter->delete($this->buildKey($key));
    }

    /**
     * {@inheritdoc }
     */
    public function get($key)
    {
        return $this->unpackData(
            $this->adapter->get(
                $this->buildKey($key)
            )
        );
    }

    /**
     * {@inheritdoc }
     */
    public function has($key)
    {
        /* It seems that the most efficient way to check has in memcached is
           by using an append with an empty string. However, we need to make
           sure that OPT_COMPRESSION is turned off because you can't append
           if you compressing data */

        /* store for later use */
        $cur_compression = $this->adapter->getOption(BaseMemcached::OPT_COMPRESSION);

        /* set compression off */
        $this->adapter->setOption(BaseMemcached::OPT_COMPRESSION, false);

        $res = $this->adapter->append(
            $this->buildKey($key),
            ''
        );

        $this->adapter->setOption(BaseMemcached::OPT_COMPRESSION, $cur_compression);

        return $res;
    }

    /**
     * {@inheritdoc }
     */
    public function set($key, $value, $ttl = null)
    {
        $this->adapter->set(
            $this->buildKey($key),
            $this->packData($value),
            $ttl ?: $this->ttl
        );
    }
}
