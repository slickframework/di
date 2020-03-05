<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Psr\Container\ContainerInterface as InteropContainer;

/**
 * Implementation methods for ContainerAwareInterface
 *
 * @package Slick\Di
 * @author  Filipe Silva <sivam.filipe@gmail.com>
 */
trait ContainerAwareMethods
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Get container
     *
     * @return InteropContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set container
     *
     * @param InteropContainer $container
     *
     * @return self|$this|ContainerAwareMethods
     */
    public function setContainer(InteropContainer $container)
    {
        $this->container = $container;
        return $this;
    }
}
