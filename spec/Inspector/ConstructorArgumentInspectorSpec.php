<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace spec\Slick\Di\Inspector;

use Prophecy\Argument;
use Slick\Di\ContainerInterface;
use Slick\Di\Inspector\ConstructorArgumentInspector;
use PhpSpec\ObjectBehavior;

/**
 * ConstructorArgumentInspectorSpec
 *
 * @package spec\Slick\Di\Inspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ConstructorArgumentInspectorSpec extends ObjectBehavior
{

    function let(
        \ReflectionClass $reflectionClass,
        \ReflectionMethod $constructor,
        \ReflectionParameter $parameter,
        \ReflectionParameter $parameter1,
        \ReflectionNamedType $typeHinted,
        ContainerInterface $container
    )
    {
        $reflectionClass->getConstructor()->willReturn($constructor);
        $constructor->getParameters()->willReturn([$parameter1, $parameter]);

        $parameter->getType()->willReturn($typeHinted);
        $parameter->getName()->willReturn('argument');
        $parameter->allowsNull()->willReturn(true);
        $parameter1->getType()->willReturn($typeHinted);
        $parameter1->getName()->willReturn('argument1');
        $parameter1->allowsNull()->willReturn(true);
        $typeHinted->getName()->willReturn(\stdClass::class);
        $container->get(\stdClass::class)->willReturn((object)[]);
        $container->has(Argument::any())->willReturn(false);
        $typeHinted->isBuiltin()->willReturn(false);

        $this->beConstructedWith($reflectionClass, $container, ['@test']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ConstructorArgumentInspector::class);
    }

    function it_get_the_types_of_all_constructor_arguments(
        \ReflectionNamedType $typeHinted
    )
    {
        $this->arguments()->shouldBeArray();
        $typeHinted->getName()->shouldHaveBeenCalledTimes(1);
    }

    function it_merges_the_defined_parameters_with_the_custom_ones()
    {
        $this->arguments()->shouldBeLike([
            '@test', (object)[]
        ]);
    }
}
