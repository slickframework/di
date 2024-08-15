<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\Di\Inspector;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionMethod;
use ReflectionParameter;
use Slick\Di\ContainerInterface;
use Slick\Di\Exception\NotFoundException;

/**
 * MethodArgumentInspector
 *
 * @package Slick\Di\Inspector
 */
final class MethodArgumentInspector
{
    private array $arguments;

    /**
     * Creates a MethodArgumentInspector
     *
     * @param ReflectionMethod $method
     * @param ContainerInterface $container
     * @param array $override
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        private readonly ReflectionMethod $method,
        private readonly ContainerInterface $container,
        private readonly array $override = []
    ) {
        $this->arguments = array_replace($this->definedArguments(), $this->override);
    }

    public function arguments(): array
    {
        return $this->arguments;
    }

    /**
     * Get the list of arguments from constructor defined parameters
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function definedArguments(): array
    {
        $arguments = [];

        if (null === $this->method) {
            return $arguments;
        }

        $parameters = $this->method->getParameters();
        $count = 1;
        foreach ($parameters as $parameter) {
            if ($count++ <= count($this->override)) {
                $arguments[] = null;
                continue;
            }

            $this->parseAttribute($arguments, $parameter);
        }
        return $arguments;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function parseAttribute(array &$arguments, ReflectionParameter $parameter): void
    {
        $name = $parameter->getName();
        $parameterType = $parameter->getType();

        $isBuiltInType = is_null($parameterType) || $parameterType->isBuiltin();
        $isNullable = !$this->container->has($name) && $parameter->allowsNull();

        if ($isBuiltInType) {
            $arguments[] = $isNullable ? null : $this->container->get($name);
            return;
        }

        try {
            $value = $this->container->get($parameterType->getName());
            $arguments[] = $value;
        } catch (NotFoundException $foundException) {
            if ($parameter->allowsNull()) {
                $arguments[] = null;
                return;
            }

            throw $foundException;
        }
    }
}
