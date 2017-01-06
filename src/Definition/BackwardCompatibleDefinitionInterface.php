<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

/**
 * Backward Compatible Definition Interface
 *
 * @see https://github.com/slickframework/di/issues/1
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
interface BackwardCompatibleDefinitionInterface
{

    /**
     * Sets constructor arguments used on instance instantiation
     *
     * @param array $arguments
     * @return $this|self
     *
     * @deprecated
     */
    public function setConstructArgs(array $arguments);

    /**
     * Set a method to be called when resolving this definition
     *
     * @param string $name      Method name
     * @param array  $arguments Method parameters
     *
     * @return $this|self
     *
     * @deprecated
     */
    public function setMethod($name, array $arguments = []);

    /**
     * Sets property value when resolving this definition
     *
     * @param string $name  The property name
     * @param mixed  $value The property value
     *
     * @return $this|self
     *
     * @deprecated
     */
    public function setProperty($name, $value);
}
