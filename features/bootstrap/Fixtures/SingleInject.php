<?php

/**
 * This file is part of Di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixtures;


class SingleInject
{

    /**
     * @var string Property to check
     */
    public $value;

    /**
     * @param string $value
     *
     * @inject singleInject
     *
     * @return SingleInject
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
