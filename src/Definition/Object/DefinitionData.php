<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition\Object;

/**
 * Definition Data for object creation
 *
 * @package Slick\Di\Definition\Object
 */
class DefinitionData
{

    const METHOD   = 'method';
    const PROPERTY = 'property';

    /**
     * @var string
     */
    protected $className;

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var array
     */
    protected $calls = [];

    /**
     * Definition Data
     *
     * @param string $className
     * @param array  $arguments
     * @param array  $calls
     */
    public function __construct($className, array $arguments = [], array $calls = [])
    {

        $this->className = $className;
        $this->arguments = $arguments;
        $this->calls = $calls;
    }

    /**
     * Adds a call to the stack
     *
     * @param string $type
     * @param string $name
     * @param array  $arguments
     *
     * @return DefinitionData
     */
    public function addCall($type, $name, $arguments = [])
    {
        array_push($this->calls, [
            'type' => $type,
            'name' => $name,
            'arguments' => $arguments
        ]);
        return $this;
    }

    /**
     * Updated the arguments of the last defined method
     *
     * @param array $arguments
     *
     * @return DefinitionData
     */
    public function updateLastMethod($arguments)
    {
        return $this->update(self::METHOD, $arguments);
    }

    /**
     * Updated the arguments of the last defined property
     *
     * @param mixed $value
     *
     * @return DefinitionData
     */
    public function updateLastProperty($value)
    {
        return $this->update(self::PROPERTY, $value);
    }

    /**
     * @param string $type
     * @param mixed  $arguments
     *
     * @return DefinitionData
     */
    protected function update($type, $arguments)
    {
        $reversed = array_reverse($this->calls);
        foreach ($reversed as &$call) {
            if ($call['type'] == $type) {
                $call['arguments'] = $arguments;
                break;
            }
        }
        $this->calls = array_reverse($reversed);
        return $this;
    }

    public function arguments()
    {
        return $this->arguments;
    }

    public function withArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function className()
    {
        return $this->className;
    }

    public function calls()
    {
        return $this->calls;
    }
}
