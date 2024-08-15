<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fixtures;

/**
 * ObjectWithInterfaceDependency
 *
 * @package Fixtures
 */
final class ObjectWithInterfaceDependency
{

    public function __construct(
        private DependencyWithDependency $dependency,
        private DependencyInterface $dependencyInterface,
    ) {
    }

    public function dependency(): DependencyWithDependency
    {
        return $this->dependency;
    }

    public function dependencyInterface(): DependencyInterface
    {
        return $this->dependencyInterface;
    }
}
