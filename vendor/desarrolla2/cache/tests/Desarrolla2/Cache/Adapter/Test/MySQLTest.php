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
use Desarrolla2\Cache\Adapter\MySQL;

/**
 * MySQLTest
 */
class MySQLTest extends AbstractCacheTest
{
    public function setUp()
    {
        parent::setup();
        if (!extension_loaded('mysqlnd')) {
            $this->markTestSkipped(
                'The MySQLnd extension is not available.'
            );
        }
        $this->cache = new Cache(
            new MySQL(
                $this->config['mysql']['host'],
                $this->config['mysql']['user'],
                $this->config['mysql']['password'],
                $this->config['mysql']['database'],
                $this->config['mysql']['port']
            )
        );
    }

    /**
     * @return array
     */
    public function dataProviderForOptions()
    {
        return array(
            array('ttl', 100),
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
}
