<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Service\Other;

use Slick\Di\Definition\ObjectDefinition;

$services = [];

$services['otherService'] = ObjectDefinition::create(\Fixtures\Object::class);

return $services;