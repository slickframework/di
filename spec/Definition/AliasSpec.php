<?php

namespace spec\Slick\Di\Definition;

use Slick\Di\ContainerInterface;
use Slick\Di\Definition\Alias;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slick\Di\DefinitionInterface;
use Slick\Di\Exception\ContainerNotSetException;

class AliasSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('@foo');
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(Alias::class);
    }

    function its_a_definition()
    {
        $this->shouldBeAnInstanceOf(DefinitionInterface::class);
    }

    function it_uses_the_container_to_search_for_other_definitions(ContainerInterface $container)
    {
        $container->get('foo')->willReturn('bar');
        $this->setContainer($container);
        $this->resolve()->shouldReturn('bar');
    }

    function it_throws_container_not_set_exception_if_no_container_is_present()
    {
        $this->shouldThrow(ContainerNotSetException::class)
            ->during('resolve');
    }
}
