<?php

namespace spec\Slick\Di;

class DryClass
{
    public $value;

    /**
     * @inject injectedValue
     *
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}