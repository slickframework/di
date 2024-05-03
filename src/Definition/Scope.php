<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

/**
 * Scope used in the definition
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 */
class Scope
{
    private $value;

    const SINGLETON = 'singleton';
    const PROTOTYPE = 'prototype';

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function Singleton()
    {
        return new Scope(Scope::SINGLETON);
    }

    public static function Prototype()
    {
        return new Scope(Scope::PROTOTYPE);
    }

    public function __toString()
    {
        return $this->value;
    }
}
