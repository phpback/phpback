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


//build test data outside of timing loop
$data = array();
for ($i = 1; $i <= 10000; $i++) {
    $data[$i] = md5($i);
}

$timer = new \Desarrolla2\Timer\Timer(new \Desarrolla2\Timer\Formatter\Human());
for ($i = 1; $i <= 10000; $i++) {
    $cache->set($data[$i], $data[$i], 3600);
}
$timer->mark('10.000 set');
for ($i = 1; $i <= 10000; $i++) {
    $cache->has($data[$i]);
}
$timer->mark('10.000 has');
for ($i = 1; $i <= 10000; $i++) {
    $cache->get($data[$i]);
}
$timer->mark('10.000 get');
for ($i = 1; $i <= 10000; $i++) {
    $cache->has($data[$i]);
    $cache->get($data[$i]);
}
$timer->mark('10.000 has+get combos');

$benchmarks = $timer->getAll();
foreach ($benchmarks as $benchmark) {
    ld($benchmark);
}
