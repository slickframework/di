<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

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
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Set container
     *
     * @param ContainerInterface $container
     *
     * @return self|$this|ContainerAwareMethods
     */
    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;
        return $this;
    }
}
