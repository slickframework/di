<?php

/**
 * This file is part of Di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

/**
 * Backward Compatible Methods Trait
 *
 * @see https://github.com/slickframework/di/issues/1
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
trait BackwardCompatibleMethodsTrait
{

    /**
     * Sets constructor arguments used on instance instantiation
     *
     * @param array $arguments
     * @return $this|self
     *
     * @deprecated You should use ObjectDefinition::withConstructorArgument().
     *             This will be removed in the next major release.
     */
    public function setConstructArgs(array $arguments)
    {
        return call_user_func_array(
            [$this, 'withConstructorArgument'],
            $arguments
        );
    }

    /**
     * Set a method to be called when resolving this definition
     *
     * @param string $name      Method name
     * @param array  $arguments Method parameters
     *
     * @return $this|self
     *
     * @deprecated You should use ObjectDefinition::callMethod(). This
     *             will be removed in the next major release.
     */
    public function setMethod($name, array $arguments = [])
    {
        $arguments = array_merge([$name], $arguments);
        return call_user_func_array(
            [$this, 'callMethod'],
            $arguments
        );
    }

    /**
     * Sets property value when resolving this definition
     *
     * @param string $name  The property name
     * @param mixed  $value The property value
     *
     * @return $this|self
     *
     * @deprecated You should use ObjectDefinition::assignProperty(). This
     *             will be removed in the next major release.
     */
    public function setProperty($name, $value)
    {
        return call_user_func_array(
            [$this, 'assignProperty'],
            [$name, $value]
        );
    }
}
