<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixtures;


class ComplexObject
{

    public $alias;
    private $value;
    public $other;

    public function __construct($alias, $value = null)
    {
        $this->alias = $alias;
        $this->value = $value;
    }

    public function setTest($value)
    {
        $this->value = $value;
    }
}
