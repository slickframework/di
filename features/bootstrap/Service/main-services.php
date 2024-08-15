<?php

/**
 * This file is part of Di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Service;

use Fixtures\ComplexObject;
use Slick\Di\Definition\ObjectDefinition;

$services = [];

// Scalar definitions
$services['config.env'] = 'development';

// Alias
$services['environment'] = '@config.env';

$services['foo.service'] = 'foo';

// Callback
$services['callable'] = function () {
    return new \DateTime('now');
};

$services['complexObject'] = ObjectDefinition::create(ComplexObject::class)
    ->with('@callable')
    ->call('setTest')->with('@environment');

return $services;
