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

use Symfony\Component\Yaml\Yaml;

/**
 * AbstractCacheTest
 */
abstract class AbstractCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Desarrolla2\Cache\Cache
     */
    protected $cache;

    /**
     * @var array
     */
    protected $config = array();

    public function setup()
    {
        $configurationFile = realpath(__DIR__.'/../../../../').'/config.yml';

        if (!is_file($configurationFile)) {
            throw new \Exception(' Configuration file not found in "'.$configurationFile.'" ');
        }
        $this->config = Yaml::parse(file_get_contents($configurationFile));
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array('key1', 'value', 1),
            array('key2', 'value', 100),
            array('key3', 'value', null),
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
     *
     * @dataProvider dataProvider
     * @param string   $key
     * @param mixed    $value
     * @param int|null $ttl
     */
    public function testHash($key, $value, $ttl)
    {
        $this->assertNull($this->cache->delete($key));
        $this->assertFalse($this->cache->has($key));
        $this->assertNull($this->cache->set($key, $value, $ttl));
        $this->assertTrue($this->cache->has($key));
    }

    /**
     *
     * @dataProvider dataProvider
     * @param string   $key
     * @param mixed    $value
     * @param int|null $ttl
     */
    public function testGet($key, $value, $ttl)
    {
        $this->cache->set($key, $value, $ttl);
        $this->assertEquals($value, $this->cache->get($key));
    }

    /**
     *
     * @dataProvider dataProvider
     * @param string   $key
     * @param mixed    $value
     * @param int|null $ttl
     */
    public function testDelete($key, $value, $ttl)
    {
        $this->cache->set($key, $value, $ttl);
        $this->assertNull($this->cache->delete($key));
        $this->assertFalse($this->cache->has($key));
    }

    /**
     * @dataProvider dataProviderForOptions
     * @param string $key
     * @param mixed  $value
     */
    public function testSetOption($key, $value)
    {
        $this->assertTrue($this->cache->setOption($key, $value));
    }

    /**
     * @dataProvider dataProviderForOptionsException
     * @param string     $key
     * @param mixed      $value
     * @param \Exception $expectedException
     */
    public function testSetOptionException($key, $value, $expectedException)
    {
        $this->setExpectedException($expectedException);
        $this->cache->setOption($key, $value);
    }

    public function testHasWithTtlExpired()
    {
        $key = 'key1';
        $value = 'value1';
        $ttl = 1;
        $this->cache->set($key, $value, $ttl);
        sleep($ttl + 1);
        $this->assertFalse($this->cache->has($key));
    }
}
