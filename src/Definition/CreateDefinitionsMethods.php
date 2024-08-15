<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\Di\Definition;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use Slick\Di\Container;
use Slick\Di\Definition\Attributes\Autowire;
use Slick\Di\DefinitionInterface;
use Slick\Di\Inspector\ConstructorArgumentInspector;
use Slick\Di\Inspector\MethodArgumentInspector;

/**
 * CreateDefinitionsMethods
 *
 * @package Slick\Di\Definition
 */
trait CreateDefinitionsMethods
{

    /**
     * Creates the definition for registered data
     *
     * If value is a callable then the definition is Factory, otherwise
     * it will create a Value definition.
     *
     * @param callable|mixed $value
     * @param array $parameters
     *
     * @return Value|Alias|Factory
     * @see Factory, Value
     *
     */
    protected function createDefinition(
        mixed $value,
        array $parameters = []
    ): Value|Alias|Factory {
        if (is_callable($value)) {
            return new Factory($value, $parameters);
        }
        return $this->createValueDefinition($value);
    }

    /**
     * Creates a definition for provided name and value pair
     *
     * If $value is a string prefixed with '@' it will create an Alias
     * definition. Otherwise, a Value definition will be created.
     *
     * @param mixed  $value
     *
     * @return Value|Alias
     */
    protected function createValueDefinition(mixed $value): Value|Alias
    {
        if (is_string($value) && str_contains($value, '@')) {
            return new Alias($value);
        }

        return new Value($value);
    }

    /**
     * @param string $className
     * @param Container $container
     * @param mixed ...$arguments
     * @return DefinitionInterface
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function createFromClass(string $className, Container $container, ...$arguments): DefinitionInterface
    {
        $definition = (new ObjectDefinition($className))
            ->setContainer($container)
        ;

        $classReflection = new ReflectionClass($className);
        $arguments = (new ConstructorArgumentInspector(
            $classReflection,
            $container,
            $arguments
        ))
            ->arguments();
        call_user_func_array([$definition, 'with'], $arguments);

        foreach ($classReflection->getMethods() as $method) {
            $autowireAttributes = $method->getAttributes(Autowire::class);
            if (empty($autowireAttributes)) {
                continue;
            }
            $attribute = $autowireAttributes[0]->newInstance();
            call_user_func_array([$definition, 'call'], [$method->getName()]);
            call_user_func_array(
                [$definition, 'with'],
                (new MethodArgumentInspector($method, $container, $attribute->services()))->arguments()
            );
        }

        return $definition;
    }
}
