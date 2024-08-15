<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Di\Definition\Object\DefinitionData;
use Slick\Di\DefinitionInterface;
use Slick\Di\Exception\MethodNotFoundException;

/**
 * Object Definition Interface
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ObjectDefinitionInterface extends DefinitionInterface
{

    /**
     * Get the definition data
     *
     * @return DefinitionData
     */
    public function getDefinitionData();

    /**
     * Adds the arguments to be used with constructor
     *
     * @param array ...$arguments
     *
     * @return self|ObjectDefinitionInterface
     */
    public function withConstructorArgument(...$arguments);

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
    public function callMethod($methodName, ...$arguments);

    /**
     * Assigns a value to the property with provided name
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return self|Object
     */
    public function assignProperty($name, $value);
}
