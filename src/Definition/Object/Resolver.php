<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition\Object;

use Slick\Di\ContainerAwareMethods;

/**
 * Object definition Resolver
 *
 * @package Slick\Di\Definition\Object
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Resolver implements ResolverInterface
{

    /**
     * @var DefinitionData
     */
    protected $data;

    /**
     * @var object
     */
    protected $object;

    /**
     * @var \ReflectionClass
     */
    protected $reflection;

    /**
     * Used to implement the ContainerAwareInterface
     */
    use ContainerAwareMethods;

    /**
     * Resolves the provided data into and object
     *
     * @param DefinitionData $data
     *
     * @return object
     */
    public function resolve(DefinitionData $data)
    {
        $this->data = $data;

        $this->object = $this->createObject();

        foreach ($data->calls as $call) {
            $this->apply($call);
        }

        return $this->object;
    }

    /**
     * Creates the object
     *
     * @return object
     */
    public function createObject()
    {
        $reflection = new \ReflectionClass($this->data->className);
        return $reflection->hasMethod('__construct')
            ? $reflection->newInstanceArgs(
                $this->filterArguments($this->data->arguments)
            )
            : $reflection->newInstance();
    }

    /**
     * Invoke a method with optional arguments on current object
     *
     * @param array $call
     *
     * @return Resolver
     */
    protected function apply($call)
    {
        if ($call['type'] !== DefinitionData::METHOD) {
            return $this->setProperty($call);
        }

        $this->getReflection()
            ->getMethod($call['name'])
            ->invokeArgs(
                $this->object,
                $this->filterArguments($call['arguments'])
            )
        ;
        return $this;
    }

    /**
     * Assign the call value to a property
     *
     * @param array $data
     *
     * @return Resolver
     */
    protected function setProperty($data)
    {
        $this->getReflection()
            ->getProperty($data['name'])
            ->setValue(
                $this->object,
                $this->filterValue($data['arguments'])
            )
        ;
        return $this;
    }

    /**
     * Filters all the arguments for aliases
     *
     * If an argument is prefixed with an '@' its value will be retrieved
     * from the container.
     *
     * @param array $data
     *
     * @return array
     */
    protected function filterArguments(array $data)
    {
        $values = [];
        foreach ($data as $argument) {
            array_push($values, $this->filterValue($argument));
        }
        return $values;
    }

    /**
     * Filters the value for aliases cases
     *
     * If the value is a string with an '@' prefix the it should try to get
     * value from the container
     *
     * @param string $value
     *
     * @return mixed
     */
    protected function filterValue($value)
    {
        if (is_string($value) && strpos($value, '@') !== false) {
            $key = substr($value, 1);
            return $this->getContainer()->get($key);
        }
        return $value;
    }

    /**
     * Get object reflection class
     *
     * @return \ReflectionClass
     */
    protected function getReflection()
    {
        if (!$this->reflection) {
            $this->reflection = new \ReflectionClass($this->object);
        }
        return $this->reflection;
    }
}
