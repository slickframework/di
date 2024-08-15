<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di\Inspector;

use ReflectionClass;
use ReflectionMethod;
use Slick\Di\ContainerInterface;

/**
 * ConstructorArgumentInspector
 *
 * @package Slick\Di\Inspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
*/
final readonly class ConstructorArgumentInspector
{
    /**
     * Creates a ConstructorArgumentInspector
     *
     * @param ReflectionClass $reflectionClass
     * @param ContainerInterface $container
     * @param array<string|int, mixed> $override
     */
    public function __construct(
        private ReflectionClass $reflectionClass,
        private ContainerInterface $container,
        private array $override = []
    ) {
    }

    /**
     * Returns the list of alias to used as arguments on object definition
     *
     * @return array
     */
    public function arguments(): array
    {
        $method = $this->reflectionClass->getConstructor();
        if (!$method instanceof ReflectionMethod) {
            return [];
        }

        return (new MethodArgumentInspector(
            $method,
            $this->container,
            $this->override
        ))->arguments();
    }
}
