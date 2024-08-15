<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Service\Other;

use Fixtures\SomeObject;
use Slick\Di\Definition\ObjectDefinition;

$services = [];

$services['environment'] = 'development';

$services[SomeObject::class] = '@otherService';
$services['otherService'] = ObjectDefinition::create(SomeObject::class);

return $services;
