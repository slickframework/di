<?php

namespace Slick\Di\Configuration;

use Slick\Di\Definition\Alias;

$services = [];

$services['foo'] = 'bar';
$services['baz'] = '@foo';
$services['test'] = new Alias('foo');

return $services;
