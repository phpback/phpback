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
use Desarrolla2\Cache\Adapter\Mongo;

/**
 * MongoTest
 */
class MongoTest extends AbstractCacheTest
{
    public function setUp()
    {
        parent::setup();
        if (!class_exists('Mongo')) {
            $this->markTestSkipped(
                'The Mongo extension is not available.'
            );
        }
        $this->cache = new Cache(new Mongo($this->config['mongo']['dns']));
    }

    /**
     * @return array
     */
    public function dataProviderForOptionsException()
    {
        return array(
            array('ttl', 0, '\Desarrolla2\Cache\Exception\MongoCacheException'),
            array('file', 100, '\Desarrolla2\Cache\Exception\MongoCacheException'),
        );
    }
}
