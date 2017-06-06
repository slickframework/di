<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Inspector;

/**
 * ConstructorArgumentInspector
 *
 * @package Slick\Di\Inspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
*/
class ConstructorArgumentInspector
{
    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var array
     */
    private $override;

    /**
     * Creates a ConstructorArgumentInspector
     *
     * @param \ReflectionClass $reflectionClass
     * @param array $override
     */
    public function __construct(\ReflectionClass $reflectionClass, array $override = [])
    {
        $this->reflectionClass = $reflectionClass;
        $this->override = $override;
    }

    /**
     * Returns the list of alias to used as arguments on object definition
     *
     * @return array
     */
    public function arguments()
    {
        $arguments = $this->definedArguments();
        return array_replace($arguments, $this->override);
    }

    /**
     * Get the list of arguments from constructor defined parameters
     *
     * @return string[]
     */
    private function definedArguments()
    {
        $arguments = [];
        $constructor = $this->reflectionClass->getConstructor();

        if (null === $constructor) {
            return $arguments;
        }

        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            $class = $parameter->getClass();
            if (is_null($class)) {
                break;
            }

            $arguments[] = "@{$parameter->getClass()->getName()}";
        }
        return $arguments;
    }

}
