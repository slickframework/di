<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Interop\Container\ContainerInterface as InteropContainer;

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
        $this->definitions = $definitions;
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
        if (! is_array($definitions)) {
            $this->hydrateFromFile($definitions);
            return;
        }

        foreach ($definitions as $name => $definition) {
            $this->container->register($name, $definition);
        }
    }

    /**
     * Hydrate the container with definitions from provided file
     *
     * @param $definitions
     */
    protected function hydrateFromFile($definitions)
    {
        if (! is_file($definitions)) {
            $this->hydrateFromDirectory($definitions);
            return;
        }

        $services = require $definitions;
        $this->hydrateContainer($services);
    }

    protected function hydrateFromDirectory($definitions)
    {
        $directory = new \RecursiveDirectoryIterator($definitions);
        $iterator = new \RecursiveIteratorIterator($directory);
        $phpFiles = new \RegexIterator($iterator, '/.*\.php$/i');
        foreach ($phpFiles as $phpFile) {
            $this->hydrateFromFile($phpFile);
        }
    }
}
