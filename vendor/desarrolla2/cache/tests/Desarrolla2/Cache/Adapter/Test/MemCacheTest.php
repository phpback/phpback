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

namespace Desarrolla2\Cache\Adapter\Test;

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\MemCache;

/**
 * MemCacheTest
 */
class MemCacheTest extends AbstractCacheTest
{
    public function setUp()
    {
        parent::setup();
        if (!extension_loaded('memcached') || !class_exists('\Memcache')) {
            $this->markTestSkipped(
                'The Memcache extension is not available.'
            );
        }

        $adapter = new MemCache();
        $adapter->addServer('localhost', 11211);
        $this->cache = new Cache($adapter);
    }

    /**
     * @return array
     */
    public function dataProviderForOptionsException()
    {
        return array(
            array('ttl', 0, '\Desarrolla2\Cache\Exception\CacheException'),
            array('file', 100, '\Desarrolla2\Cache\Exception\CacheException'),
        );
    }
}
