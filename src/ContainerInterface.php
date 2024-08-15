<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Psr\Container\ContainerInterface as InteropContainer;
use Slick\Di\Definition\Scope;

interface ContainerInterface extends InteropContainer
{

    /**
     * Adds a definition or a value to the container
     *
     * This method does not override an existing entry if the same name exists
     * in the definitions or in any definitions of its parents.
     * This way it is possible to change entries defined by other packages
     * as those are build after the main application container is build.
     * The main application container SHOULD be the first to be created and
     * therefore set any entry that will override the latest containers build.
     *
     * @param string $name
     * @param mixed|null $definition
     * @param string|Scope $scope      Resolving scope
     * @param array        $parameters Used if $value is a callable
     *
     * @return Container
     */
    public function register(
        string       $name,
        mixed        $definition = null,
        string|Scope $scope = Scope::SINGLETON,
        array        $parameters = []
    ): Container;

    /**
     * Creates an instance of provided class injecting its dependencies
     *
     * @param string $className
     * @param array ...$arguments
     *
     * @return mixed
     */
    public function make(string $className, ...$arguments): mixed;
}
