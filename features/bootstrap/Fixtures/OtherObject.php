<?php

/**
 * This file is part of Di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Fixtures;


use Psr\Container\ContainerInterface;
use Slick\Di\ContainerInjectionInterface;

class OtherObject implements ContainerInjectionInterface
{

    /**
     * @var
     */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
        if ($value != '43') {
            throw new \InvalidArgumentException('Wrong number!');
        }
    }

    /**
     * Instantiates a new instance of this class.
     *
     * This is a factory method that returns a new instance of this class. The
     * factory should pass any needed dependencies into the constructor of this
     * class, but not the container itself. Every call to this method must return
     * a new instance of this class; that is, it may not implement a singleton.
     *
     * @param ContainerInterface $container
     *   The service container this instance should use.
     *
     * @return self
     */
    public static function create(ContainerInterface $container)
    {
        return new static($container->get('other-value'));
    }
}
