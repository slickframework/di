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
 * Container Aware Interface
 *
 * @package Slick\Di
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
interface ContainerAwareInterface
{

    /**
     * Set the dependency container
     *
     * @param ContainerInterface $container
     *
     * @return ContainerAwareInterface
     */
    public function setContainer(ContainerInterface $container);

    /**
     * Get the dependency container
     *
     * @return InteropContainer
     */
    public function getContainer();
}
