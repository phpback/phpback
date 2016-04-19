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

namespace Desarrolla2\Cache\Test;

use Desarrolla2\Cache\Cache;

/**
 * CacheTest
 */
class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Desarrolla2\Cache\Cache
     */
    protected $cache;

    public function setUp()
    {
        $this->cache = new Cache();
    }

    /**
     * @expectedException \Desarrolla2\Cache\Exception\AdapterNotSetException
     */
    public function testGetAdapterThrowsException()
    {
        $this->cache->getAdapter();
    }
}
