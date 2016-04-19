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

require_once __DIR__.'/../bootstrap.php';

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\File;

$cache = new Cache(new File('/tmp'));

require_once __DIR__.'/common.php';
