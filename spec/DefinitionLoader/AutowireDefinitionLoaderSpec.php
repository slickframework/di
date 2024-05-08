<?php

namespace spec\Slick\Di\DefinitionLoader;

use PhpSpec\ObjectBehavior;
use Slick\Di\ContainerAwareInterface;
use Slick\Di\DefinitionLoader\AutowireDefinitionLoader;

class AutowireDefinitionLoaderSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(__DIR__.'/AutowireDefinitionLoader/Fixtures');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AutowireDefinitionLoader::class);
    }

    function its_a_container_aware()
    {
        $this->shouldBeAnInstanceOf(ContainerAwareInterface::class);
    }
}
