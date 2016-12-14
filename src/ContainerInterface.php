<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Interop\Container\ContainerInterface as InteropContainer;
use Slick\Di\Definition\Scope;

interface ContainerInterface extends InteropContainer
{

    /**
     * Adds a definition or a value to the container
     *
     * @param string       $name
     * @param mixed        $definition
     * @param Scope|string $scope      Resolving scope
     * @param array        $parameters Used if $value is a callable
     *
     * @return Container
     */
    public function register(
        $name,
        $definition = null,
        $scope = Scope::SINGLETON,
        array $parameters = []
    );

    /**
     * Creates an instance of provided class injecting its dependencies
     *
     * @param string $className
     * @param array ...$arguments
     *
     * @return mixed
     */
    public function make($className, ...$arguments);
}
