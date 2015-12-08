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

use Desarrolla2\Cache\Exception\FileCacheException;

/**
 * File
 */
class File extends AbstractAdapter
{
    const CACHE_FILE_PREFIX = '__';
    const CACHE_FILE_SUBFIX = '.php.cache';

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @param  null $cacheDir
     *
     * @throws FileCacheException
     */
    public function __construct($cacheDir = null)
    {
        if (!$cacheDir) {
            $cacheDir = realpath(sys_get_temp_dir()).'/cache';
        }
        $this->cacheDir = (string)$cacheDir;
        if (!is_dir($this->cacheDir)) {
            if (!mkdir($this->cacheDir, 0777, true)) {
                throw new FileCacheException($this->cacheDir.' is not writable');
            }
        }
        if (!is_writable($this->cacheDir)) {
            throw new FileCacheException($this->cacheDir.' is not writable');
        }
    }

    /**
     * Delete a value from the cache
     *
     * @param string $key
     */
    public function delete($key)
    {
        $tKey = $this->getKey($key);
        $cacheFile = $this->getCacheFile($tKey);
        $this->deleteFile($cacheFile);
    }

    /**
     * {@inheritdoc }
     */
    public function get($key)
    {
        if ($data = $this->getData($key)) {
            return $data;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        if (!$this->getData($key)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc }
     */
    public function set($key, $value, $ttl = null)
    {
        $tKey = $this->getKey($key);
        $cacheFile = $this->getCacheFile($tKey);
        $tValue = $this->serialize($value);
        if (!($ttl)) {
            $ttl = $this->ttl;
        }
        $item = $this->serialize(
            [
                'value' => $tValue,
                'ttl' => (int)$ttl + time(),
            ]
        );
        if (!file_put_contents($cacheFile, $item)) {
            throw new FileCacheException('Error saving data with the key "'.$key.'" to the cache file.');
        }
    }

    /**
     * {@inheritdoc }
     */
    public function setOption($key, $value)
    {
        switch ($key) {
            case 'ttl':
                $value = (int)$value;
                if ($value < 1) {
                    throw new FileCacheException('ttl cant be lower than 1');
                }
                $this->ttl = $value;
                break;
            default:
                throw new FileCacheException('option not valid '.$key);
        }

        return true;
    }

    /**
     * {@inheritdoc }
     */
    public function clearCache()
    {
        throw new Exception('not ready yet');
    }

    /**
     * {@inheritdoc }
     */
    public function dropCache()
    {
        foreach (scandir($this->cacheDir) as $fileName) {
            $cacheFile = $this->cacheDir.
                DIRECTORY_SEPARATOR.
                $fileName;
            $this->deleteFile($cacheFile);
        }
    }

    /**
     * Delete file
     *
     * @param  string $cacheFile
     *
     * @return bool
     */
    protected function deleteFile($cacheFile)
    {
        if (is_file($cacheFile)) {
            return unlink($cacheFile);
        }

        return false;
    }

    /**
     * Get the specified cache file
     */
    protected function getCacheFile($fileName)
    {
        return $this->cacheDir.
        DIRECTORY_SEPARATOR.
        self::CACHE_FILE_PREFIX.
        $fileName.
        self::CACHE_FILE_SUBFIX;
    }

    /**
     * Get data value from file cache
     *
     * @param  string $key
     *
     * @return mixed
     * @throws FileCacheException
     */
    protected function getData($key)
    {
        $tKey = $this->getKey($key);
        $cacheFile = $this->getCacheFile($tKey);
        if (!file_exists($cacheFile)) {
            return;
        }
        if (!$data = unserialize(file_get_contents($cacheFile))) {
            throw new FileCacheException(
                'Error with the key "'.$key.'" in cache file '.$cacheFile
            );
        }
        if (!array_key_exists('value', $data)) {
            throw new FileCacheException(
                'Error with the key "'.$key.'" in cache file '.$cacheFile.', value not exist'
            );
        }
        if (!array_key_exists('ttl', $data)) {
            throw new FileCacheException(
                'Error with the key "'.$key.'" in cache file '.$cacheFile.', ttl not exist'
            );
        }
        if (time() > $data['ttl']) {
            $this->delete($key);

            return;
        }

        return $this->unserialize($data['value']);
    }
}
