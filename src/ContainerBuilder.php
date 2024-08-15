<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Exception;
use Slick\Di\DefinitionLoader\DirectoryDefinitionLoader;

/**
 * Container Builder
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
*/
final class ContainerBuilder implements ContainerBuilderInterface
{

    private ?ContainerInterface $container = null;

    /**
     * @var array|string
     */
    private string|array $definitions = [];

    /**
     * @throws Exception|\Slick\Di\Exception
     */
    public function __construct($definitions = [])
    {
        if (is_array($definitions)) {
            $this->definitions = $definitions;
            return;
        }

        $this->load(new DirectoryDefinitionLoader($definitions));
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function load(DefinitionLoaderInterface $loader): ContainerBuilder
    {
        if ($loader instanceof ContainerAwareInterface) {
            $loader->setContainer($this->getContainer());
        }
        $definitions = (array) $loader->getIterator();
        $this->definitions = array_merge($this->definitions, $definitions);
        $this->setContainer(new Container());
        return $this;
    }

    /**
     * Get current container
     *
     * If no container was created a new, empty container will be created.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if (!$this->container) {
            $this->setContainer(new Container());
        }
        return $this->container;
    }


    /**
     * Set the dependency container
     *
     * @param ContainerInterface $container
     *
     * @return ContainerBuilder
     */
    public function setContainer(ContainerInterface $container): ContainerBuilder
    {
        $this->container = $container;
        $this->hydrateContainer($this->definitions);
        return $this;
    }

    /**
     * Hydrates the container with provided definitions
     *
     * @param array $definitions
     */
    protected function hydrateContainer(array $definitions): void
    {
        foreach ($definitions as $name => $definition) {
            $this->container->register($name, $definition);
        }
    }
}
