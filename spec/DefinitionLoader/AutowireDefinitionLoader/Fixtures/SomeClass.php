<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DefinitionLoader\AutowireDefinitionLoader\Fixtures;

use Behat\Testwork\Event\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * SomeClass
 *
 * @package DefinitionLoader\AutowireDefinitionLoader
 */
final class SomeClass extends Event implements EventSubscriberInterface, \Stringable
{

    public static function getSubscribedEvents(): array
    {
        return [];
    }

    public function __toString(): string
    {
        return 'test';
    }
}
