<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fixtures\Interfaces;

/**
 * InterfaceDependentObject
 *
 * @package Fixtures
 */
final readonly class InterfaceDependentObject
{

    public function __construct(private DefinedInterface $dependency)
    {
    }

    public function dependency(): DefinedInterface
    {
        return $this->dependency;
    }
}
