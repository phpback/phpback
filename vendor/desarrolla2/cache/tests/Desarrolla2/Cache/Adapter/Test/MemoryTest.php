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
use Desarrolla2\Cache\Adapter\Memory;

/**
 * MemoryTest
 */
class MemoryTest extends AbstractCacheTest
{
    public function setUp()
    {
        $this->cache = new Cache(new Memory());
    }

    /**
     * @return array
     */
    public function dataProviderForOptions()
    {
        return array(
            array('ttl', 100),
            array('limit', 100),
        );
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

    public function testExceededLimit()
    {
        $limit = 1;
        $this->cache->setOption('limit', $limit);
        for ($i = 0; $i <= $limit; $i++) {
            $this->cache->set($i, $i);
        }
        $this->assertFalse($this->cache->has($i));
    }
}
