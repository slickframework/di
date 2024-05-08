<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fixtures;

/**
 * DependencyWithDependency
 *
 * @package Fixtures
 */
final readonly class DependencyWithDependency
{

    public function __construct(private BaseDependency $dependency)
    {
    }

    public function dependency(): BaseDependency
    {
        return $this->dependency;
    }
}
