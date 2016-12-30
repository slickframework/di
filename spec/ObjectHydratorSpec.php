<?php

namespace spec\Slick\Di;

use Slick\Di\ContainerAwareInterface;
use Slick\Di\ContainerInterface;
use Slick\Di\ObjectHydrator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slick\Di\ObjectHydratorInterface;

require_once 'DryClass.php';

class ObjectHydratorSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ObjectHydrator::class);
    }

    function its_an_object_hydrator()
    {
        $this->shouldHaveType(ObjectHydratorInterface::class);
    }

    function it_hydrates_objects_calling_methods_with_inject_annotation(
        ContainerInterface $container
    )
    {
        $container->get('injectedValue')->willReturn('test inject');
        $this->beConstructedWith($container);
        $dryObject = New DryClass();
        $this->hydrate($dryObject);
    }
}
