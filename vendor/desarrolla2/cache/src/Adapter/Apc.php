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

use Desarrolla2\Cache\Exception\ApcCacheException;

/**
 * Apc
 */
class Apc extends AbstractAdapter
{

    private $apcu;
    
    public function __construct()
    {
        $this->apcu = extension_loaded('apcu');
    }
	
    /**
     * Delete a value from the cache
     *
     * @param  string                                         $key
     * @throws \Desarrolla2\Cache\Exception\ApcCacheException
     */
    public function delete($key)
    {
        if ($this->has($key)) {
            $tKey = $this->getKey($key);
            if (!($this->apcu ? \apcu_delete($tKey) : \apc_delete($tKey))) {
                throw new ApcCacheException('Error deleting data with the key "'.$key.'" from the APC cache.');
            }
        }
    }

    /**
     * {@inheritdoc }
     */
    public function get($key)
    {
        if ($this->has($key)) {
            $tKey = $this->getKey($key);
            if (!$data = ($this->apcu ? \apcu_fetch($tKey) : \apc_fetch($tKey))) {
                throw new ApcCacheException('Error fetching data with the key "'.$key.'" from the APC cache.');
            }

            return $this->unserialize($data);
        }

        return null;
    }

    /**
     * {@inheritdoc }
     */
    public function has($key)
    {
        $tKey = $this->getKey($key);

        if (function_exists("\apcu_exists") || function_exists("\apc_exists")) {
            return (boolean) ($this->apcu ? \apcu_exists($tKey) : \apc_exists($tKey));
        } else {
            $result = false;
            ($this->apcu ? \apcu_fetch($tKey, $result) : \apc_fetch($tKey, $result));

            return (boolean) $result;
        }
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
        if (!($this->apcu ? \apcu_store($tKey, $tValue, $ttl) : \apc_store($tKey, $tValue, $ttl))) {
            throw new ApcCacheException('Error saving data with the key "'.$key.'" to the APC cache.');
        }
    }

    /**
     * {@inheritdoc }
     */
    public function setOption($key, $value)
    {
        switch ($key) {
            case 'ttl':
                $value = (int) $value;
                if ($value < 1) {
                    throw new ApcCacheException('ttl cant be lower than 1');
                }
                $this->ttl = $value;
                break;
            default:
                throw new ApcCacheException('option not valid '.$key);
        }

        return true;
    }

    /**
     * {@inheritdoc }
     */
    public function dropCache()
    {
		($this->apcu ? apcu_clear_cache("user") : apc_clear_cache("user"));
    }
}
