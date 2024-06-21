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
 * Value definition
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Value extends AbstractDefinition implements DefinitionInterface
{

    /**
     * @var mixed
     */
    protected $data;

    /**
     * Value definition
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Resolves the definition into a scalar or object
     *
     * @return mixed
     */
    public function resolve(): mixed
    {
        return $this->data;
    }
}
