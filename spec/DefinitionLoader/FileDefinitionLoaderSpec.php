<?php

namespace spec\Slick\Di\DefinitionLoader;

use PhpSpec\ObjectBehavior;
use Slick\Di\ContainerInterface;
use Slick\Di\DefinitionInterface;
use Slick\Di\DefinitionLoader\FileDefinitionLoader;
use Slick\Di\DefinitionLoaderInterface;

class FileDefinitionLoaderSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(__DIR__.'/services.php');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FileDefinitionLoader::class);
    }

    function its_a_definition_loader()
    {
        $this->shouldBeAnInstanceOf(DefinitionLoaderInterface::class);
    }

    function it_should_load_definitions_from_file()
    {
        $this->getIterator()->shouldHaveCount(1);
        $definition = $this->getIterator()['foo'];
        $definition->shouldBeAnInstanceOf(DefinitionInterface::class);
        $definition->resolve()->shouldBe('bar');
    }
}
