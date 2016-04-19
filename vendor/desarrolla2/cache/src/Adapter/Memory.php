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

use Desarrolla2\Cache\Exception\MemoryCacheException;

/**
 * Memory
 */
class Memory extends AbstractAdapter
{
    /**
     *
     * @var int
     */
    protected $limit = 100;

    /**
     *
     * @var array
     */
    protected $cache = array();

    /**
     * Delete a value from the cache
     *
     * @param string $key
     */
    public function delete($key)
    {
        $tKey = $this->getKey($key);
        unset($this->cache[$tKey]);
    }

    /**
     * {@inheritdoc }
     */
    public function get($key)
    {
        if ($this->has($key)) {
            $tKey = $this->getKey($key);

            return $this->unserialize($this->cache[$tKey]['value']);
        }

        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function has($key)
    {
        $tKey = $this->getKey($key);
        if (isset($this->cache[$tKey])) {
            $data = $this->cache[$tKey];
            if (time() < $data['ttl']) {
                return true;
            } else {
                $this->delete($key);
            }
        }

        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function set($key, $value, $ttl = null)
    {
        while (count($this->cache) >= $this->limit) {
            array_shift($this->cache);
        }
        $tKey = $this->getKey($key);
        if (!$ttl) {
            $ttl = $this->ttl;
        }
        $this->cache[$tKey] = array(
            'value' => serialize($value),
            'ttl'   => $ttl + time(),
        );
    }

    /**
     * {@inheritdoc }
     */
    public function setOption($key, $value)
    {
        switch ($key) {
            case 'limit':
                $value = (int) $value;
                if ($value < 1) {
                    throw new MemoryCacheException('limit cant be lower than 1');
                }
                $this->limit = $value;

                return true;
        }

        return parent::setOption($key, $value);
    }
}
