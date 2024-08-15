<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Di\Exception\MethodNotFoundException;

/**
 * Fluent Object Definition
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface FluentObjectDefinitionInterface extends ObjectDefinitionInterface
{

    /**
     * Set the arguments for the last defined method call
     *
     * If no method call was defined yet it will set the constructor argument list
     *
     * @param array ...$arguments Arguments passed to object constructor
     *
     * @return FluentObjectDefinitionInterface|self
     */
    public function with(...$arguments);

    /**
     * Define a method call in the freshly created object
     *
     * @param string $methodName The method name to call
     *
     * @return FluentObjectDefinitionInterface|self
     *
     * @throws MethodNotFoundException
     */
    public function call($methodName);

    /**
     * Set the value that will be assigned to a property
     *
     * @param mixed $value
     *
     * @return self|Object
     */
    public function assign($value);

    /**
     * Assign the last defined value to the provided property
     *
     * After assign the value set with
     * FluentObjectDefinitionInterface::assign() it MUST be set to NULL so that
     * every subsequent call to FluentObjectDefinitionInterface::assign() don't
     * have this value.
     *
     * @param string $property
     *
     * @return self|Object
     */
    public function to($property);
}
