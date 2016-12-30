<?php

namespace spec\Slick\Di\Definition;

use Slick\Di\Definition\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slick\Di\DefinitionInterface;

class FactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            function (){
                return 'Test Callable';
            }
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    function its_a_definition()
    {
        $this->shouldBeAnInstanceOf(DefinitionInterface::class);
    }

    function it_resolves_to_the_callable_return()
    {
        $this->resolve()->shouldBe('Test Callable');
    }

    function it_can_have_parameters_to_pass_to_callable_on_resolve()
    {
        $this->beConstructedWith(
            function ($test) {
                return $test;
            },
            ['test with parameters']
        );
        $this->resolve()->shouldBe('test with parameters');
    }
}
