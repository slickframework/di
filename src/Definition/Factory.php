<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Di\DefinitionInterface;

/**
 * Factory definition allows creation of object with a callable
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Factory extends AbstractDefinition implements DefinitionInterface
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * Factory needs a name and a callable
     *
     * @param callable $callable
     * @param array    $parameters
     */
    public function __construct(
        callable $callable,
        array $parameters = []
    ) {
        $this->callable = $callable;
        $this->parameters = $parameters;
    }

    /**
     * Resolves the definition into a scalar or object
     *
     * @return mixed
     */
    public function resolve()
    {
        $params = array_replace([$this->container], $this->parameters);
        return call_user_func_array($this->callable, $params);
    }
}
