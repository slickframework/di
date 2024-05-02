<?php

namespace spec\Slick\Di\DefinitionLoader;

use PhpSpec\ObjectBehavior;
use Slick\Di\DefinitionInterface;
use Slick\Di\DefinitionLoader\DirectoryDefinitionLoader;
use Slick\Di\DefinitionLoaderInterface;

class DirectoryDefinitionLoaderSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(__DIR__.'/services');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DirectoryDefinitionLoader::class);
    }

    function it_should_implement_DefinitionLoaderInterface()
    {
        $this->shouldImplement(DefinitionLoaderInterface::class);
    }

    function it_loads_a_directory()
    {
        $this->getIterator()->shouldHaveCount(2);
        $definition = $this->getIterator()['foo'];
        $definition->shouldBeAnInstanceOf(DefinitionInterface::class);
        $definition->resolve()->shouldBe('bar');

        $definition = $this->getIterator()['my.value'];
        $definition->shouldBeAnInstanceOf(DefinitionInterface::class);
        $definition->resolve()->shouldBe(3);
    }
}
