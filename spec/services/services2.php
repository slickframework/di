<?php

namespace Slick\Di\Configuration;

use Slick\Di\Definition\Alias;

$services = [];

$services['foo'] = 'test';
$services['baz'] = '@foo';

return $services;
