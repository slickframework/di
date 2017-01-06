<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Di\Definition\Object\DefinitionData;
use Slick\Di\Definition\Object\Resolver;
use Slick\Di\Definition\Object\ResolverAwareInterface;
use Slick\Di\Definition\Object\ResolverInterface;
use Slick\Di\Exception\ClassNotFoundException;
use Slick\Di\Exception\MethodNotFoundException;
use Slick\Di\Exception\PropertyNotFoundException;

/**
 * Object
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ObjectDefinition extends AbstractDefinition implements
    FluentObjectDefinitionInterface,
    ResolverAwareInterface,
    BackwardCompatibleDefinitionInterface
{
    /**
     * @var DefinitionData
     */
    protected $definitionData;

    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var mixed
     */
    private $lastValue;

    /**
     * Old methods that should be removed in next major version
     */
    use BackwardCompatibleMethodsTrait;

    /**
     * Creates an object definition
     *
     * @param string $className
     *
     * @throws ClassNotFoundException if the provided class name is from an
     *         undefined or inaccessible class.
     */
    public function __construct($className)
    {
        if (! class_exists($className)) {
            throw new ClassNotFoundException(
                "The class '{$className}' does not exists or it cannot be " .
                "loaded. Object definition therefore cannot be " .
                "created."
            );
        }

        $this->definitionData = new DefinitionData($className);
    }

    /**
     * Creates an object definition
     *
     * @param string $className
     *
     * @return FluentObjectDefinitionInterface
     *
     * @throws ClassNotFoundException if the provided class name is from an
     *         undefined or inaccessible class.
     */
    public static function create($className)
    {
        return new static($className);
    }

    /**
     * Resolves the definition into a scalar or object
     *
     * @return mixed
     */
    public function resolve()
    {
        return $this->getResolver()
            ->setContainer($this->getContainer())
            ->resolve($this->definitionData);
    }

    /**
     * Set the arguments for the last defined method call
     *
     * If no method call was defined yet it will set the constructor argument list
     *
     * @param array ...$arguments Arguments passed to object constructor
     *
     * @return $this|Object|self
     */
    public function with(...$arguments)
    {

        if (empty($this->definitionData->calls)) {
            $this->getReflectionClass()
                ->getMethod('withConstructorArgument')
                ->invokeArgs($this, $arguments);
            return $this;
        }

        $this->definitionData->updateLastMethod($arguments);
        return $this;
    }

    /**
     * Set the arguments used to create the object
     *
     * @param array ...$arguments
     *
     * @return self|Object
     */
    public function withConstructorArgument(...$arguments)
    {
        $this->definitionData->arguments = $arguments;
        return $this;
    }

    /**
     * Get the object resolver
     *
     * @return ResolverInterface
     */
    public function getResolver()
    {
        if (!$this->resolver) {
            $this->setResolver(new Resolver());
        }
        return $this->resolver;
    }

    /**
     * Set the object resolver
     *
     * @param ResolverInterface $resolver
     *
     * @return self|$this
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }

    /**
     * Get the definition data
     *
     * @return DefinitionData
     */
    public function getDefinitionData()
    {
        return $this->definitionData;
    }

    /**
     * Define a method call in the freshly created object
     *
     * @param string $methodName The method name to call
     *
     * @return FluentObjectDefinitionInterface|self
     *
     * @throws MethodNotFoundException
     */
    public function call($methodName)
    {
        $this->getReflectionClass()
            ->getMethod('callMethod')
            ->invokeArgs($this, [$methodName]);
        return $this;
    }

    /**
     * Define a method call with provide call
     *
     * @param string $methodName
     * @param array ...$arguments
     *
     * @return self|ObjectDefinitionInterface
     *
     * @throws MethodNotFoundException
     */
    public function callMethod($methodName, ...$arguments)
    {

        if (! method_exists($this->definitionData->className, $methodName)) {
            throw new MethodNotFoundException(
                "The method '{$methodName}' is not defined in the class ".
                "{$this->definitionData->className}"
            );
        }

        $this->definitionData->addCall(
            DefinitionData::METHOD,
            $methodName,
            $arguments
        );
        return $this;
    }

    /**
     * Set the value that will be assigned to a property
     *
     * @param mixed $value
     *
     * @return self|Object
     */
    public function assign($value)
    {
        $this->lastValue = $value;
        return $this;
    }

    /**
     * Assign the last defined value to the provided property
     *
     * The value will be reset after its assigned.
     *
     * @param string $property
     *
     * @return self|Object
     */
    public function to($property)
    {
        if (! property_exists($this->definitionData->className, $property)) {
            throw new PropertyNotFoundException(
                "The class '{$this->definitionData->className}' has no " .
                "property called '{$property}'."
            );
        }
        $this->getReflectionClass()
            ->getMethod('assignProperty')
            ->invokeArgs($this, [$property, $this->lastValue]);

        $this->lastValue = null;
        return $this;
    }

    /**
     * Assigns a value to the property with provided name
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return self|Object
     */
    public function assignProperty($name, $value)
    {
        $this->definitionData->addCall(
            DefinitionData::PROPERTY,
            $name,
            $value
        );
        return $this;
    }

    /**
     * Get the self reflection
     *
     * @return \ReflectionClass
     */
    protected function getReflectionClass()
    {
        if (!$this->reflectionClass) {
            $this->reflectionClass = new \ReflectionClass($this);
        }
        return $this->reflectionClass;
    }
}
