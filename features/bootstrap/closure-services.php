<?php

/**
 * This file is part of Di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Service;

use Fixtures\ComplexObject;
use Slick\Di\ContainerInterface;
use Slick\Di\Definition\ObjectDefinition;

$services = [];

// Scalar definitions
$services['config.env'] = 'development';

// Alias
$services['environment'] = '@config.env';

// Callback
$services['callable'] = function () {
    return new \DateTime('now');
};

// Object definition
$services['complexObject'] = (new ObjectDefinition(ComplexObject::class))
    ->with('@callable')
    ->call('setTest')->with('@environment');

$services['closureWithContainer'] = function (ContainerInterface $container) {
    return $container->get('complexObject');
};

return $services;
