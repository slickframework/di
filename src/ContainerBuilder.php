<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Psr\Container\ContainerInterface as InteropContainer;
use Slick\Di\DefinitionLoader\DirectoryDefinitionLoader;
use Slick\Di\Exception\InvalidDefinitionsPathException;

/**
 * Container Builder
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
*/
final class ContainerBuilder implements ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array|string
     */
    private $definitions;

    public function __construct($definitions = [])
    {
        if (is_array($definitions)) {
            $this->definitions = $definitions;
            return;
        }

        $this->definitions = (array) (new DirectoryDefinitionLoader($definitions))->getIterator();
    }

    /**
     * Get current container
     *
     * If no container was created a new, empty container will be created.
     *
     * @return ContainerInterface|Container
     */
    public function getContainer()
    {
        if (!$this->container) {
            $this->setContainer(new Container());
        }
        $this->hydrateContainer($this->definitions);
        return $this->container;
    }


    /**
     * Set the dependency container
     *
     * @param InteropContainer $container
     *
     * @return ContainerBuilder
     */
    public function setContainer(InteropContainer $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Hydrates the container with provided definitions
     *
     * @param string|array $definitions
     */
    protected function hydrateContainer($definitions)
    {
        foreach ($definitions as $name => $definition) {
            $this->container->register($name, $definition);
        }
    }
}
