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
 * ObjectWithDependencies
 *
 * @package Fixtures
 */
final class ObjectWithDependencies
{

    public function __construct(private DependencyWithDependency  $dependency)
    {
    }

    public function dependency(): DependencyWithDependency
    {
        return $this->dependency;
    }
}
