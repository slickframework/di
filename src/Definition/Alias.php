<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Psr\Container\ContainerInterface;
use Slick\Di\DefinitionInterface;
use Slick\Di\Exception\ContainerNotSetException;
use Slick\Di\Exception\NotFoundException;

/**
 * Alias Definition
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Alias extends AbstractDefinition implements DefinitionInterface
{
    /**
     * @var string
     */
    protected $alias;

    /**
     * Alias needs a name and the definition name to look for in the container
     *
     * @param string $alias
     */
    public function __construct($alias)
    {
        $alias = str_replace('@', '', $alias);

        $this->alias = $alias;
    }

    /**
     * Resolves the definition into a scalar or object
     *
     * @return mixed
     *
     * @throws NotFoundException  No entry was found for alias
     *                            in current container.
     * @throws ContainerNotSetException If no container is set before
     *                                  calling resolve().
     */
    public function resolve(): mixed
    {
        if (!$this->container instanceof ContainerInterface) {
            throw new ContainerNotSetException(
                "No container was set for definition. ".
                "It is not possible to look for alias '{$this->alias}'"
            );
        }

        return $this->container->get($this->alias);
    }
}
