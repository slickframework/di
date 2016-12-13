<?php

namespace spec\Slick\Di\Definition;

use Slick\Di\Definition\Value;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slick\Di\DefinitionInterface;

class ValueSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('valueForKey');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Value::class);
    }

    function its_a_definition()
    {
        $this->shouldHaveType(DefinitionInterface::class);
    }

    function it_returns_the_value_passed_in_the_constructor()
    {
        $this->resolve()->shouldReturn('valueForKey');
    }
}
