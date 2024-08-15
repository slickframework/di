<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Psr\Container\ContainerInterface as InteropContainer;

/**
 * Container Injection Interface
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ContainerInjectionInterface
{

    /**
     * Instantiates a new instance of this class.
     *
     * This is a factory method that returns a new instance of this class. The
     * factory should pass any needed dependencies into the constructor of this
     * class, but not the container itself. Every call to this method must return
     * a new instance of this class; that is, it may not implement a singleton.
     *
     * @param InteropContainer $container
     *   The service container this instance should use.
     */
    public static function create(InteropContainer $container);
}
