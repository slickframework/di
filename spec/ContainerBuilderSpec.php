<?php

namespace spec\Slick\Di;

use Slick\Di\ContainerInterface;
use Slick\Di\ContainerAwareInterface;
use Slick\Di\ContainerBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContainerBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ContainerBuilder::class);
    }

    function its_container_aware()
    {
        $this->shouldBeAnInstanceOf(ContainerAwareInterface::class);
    }

    function it_creates_empty_containers()
    {
        $this->beConstructedWith();
        $this->getContainer()->shouldBeAnInstanceOf(ContainerInterface::class);
    }

    function it_hydrates_a_container_with_an_array_of_definitions(ContainerInterface $container)
    {
        $services = [
            'foo' => 'bar',
            'baz' => '@foo'
        ];
        $container->register(Argument::type('string'), Argument::any())->shouldBeCalledTimes(2);
        $this->beConstructedWith($services);
        $this->setContainer($container);
        $this->getContainer()->shouldBeAnInstanceOf(ContainerInterface::class);
    }

    function it_hydrates_a_container_with_an_array_from_a_file(ContainerInterface $container)
    {
        $container->register(Argument::type('string'), Argument::any())->shouldBeCalledTimes(3);
        $this->beConstructedWith(__DIR__ .'/services/services1.php');
        $this->setContainer($container);
        $this->getContainer()->shouldBeAnInstanceOf(ContainerInterface::class);
    }

    function it_hydrates_a_container_with_all_arrays_form_the_files_within_a_directory(ContainerInterface $container)
    {
        $container->register(Argument::type('string'), Argument::any())->shouldBeCalledTimes(4);
        $this->beConstructedWith(__DIR__ .'/services');
        $this->setContainer($container);
        $this->getContainer()->shouldBe($container);
    }

}
