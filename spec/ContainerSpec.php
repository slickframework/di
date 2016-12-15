<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Di;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\NotFoundException;
use Slick\Di\Container;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slick\Di\ContainerInjectionInterface;
use Slick\Di\Definition\Scope;
use Slick\Di\DefinitionInterface;
use Slick\Di\ObjectHydratorAwareInterface;
use Slick\Di\ObjectHydratorInterface;

/**
 * ContainerSpec
 *
 * @package spec\Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ContainerSpec extends ObjectBehavior
{

    function let()
    {
        $this->register('foo', 'bar');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContainerInterface::class);
    }

    function its_hydrator_aware()
    {
        $this->shouldBeAnInstanceOf(ObjectHydratorAwareInterface::class);
    }

    function it_registers_values_under_provided_keys()
    {
        $this->register('baz', 'test')->shouldBeAnInstanceOf(Container::class);
    }

    function it_returns_values_stored_under_provided_keys()
    {
        $this->get('foo')->shouldBe('bar');
    }

    function it_checks_if_a_definition_exists()
    {
        $this->has('foo')->shouldBe(true);
    }

    function it_throws_a_not_found_exception_for_unregistered_definitions()
    {
        $this->shouldThrow(NotFoundException::class)
            ->during('get', ['unknown']);
    }

    function it_can_resolve_definitions(DefinitionInterface $definition)
    {
        $definition->resolve()->willReturn('value');
        $definition->setContainer($this)->willReturn($this);
        $definition->getScope()->willReturn(Scope::SINGLETON);
        $this->register('test-value', $definition);
        $this->get('test-value')->shouldBe('value');
    }

    function it_registers_a_callable_executing_it_when_resolving()
    {
        $callable = function ($test) {
            return $test;
        };
        $this->register(
            'callable-test',
            $callable,
            Scope::Singleton(),
            ['Hello test!']
        );
        $this->get('callable-test')->shouldReturn('Hello test!');
    }

    function it_registers_an_alias_that_points_to_other_definition()
    {
        $object = (object)[];
        $this->register('test-object', $object);
        $this->register('alias', '@test-object');
        $this->get('alias')->shouldBe($object);
    }

    function it_is_aware_of_definition_resolution_scope()
    {
        $callable = function () {
            static $calls;

            if (!$calls) { $calls = 0;}

            return $calls++;
        };

        $this->register('test', $callable, Scope::Prototype());

        $first = $this->get('test');
        $this->get('test')->shouldNotBe($first);
    }

    function it_creates_objects_injecting_its_dependencies(ObjectHydratorInterface $hydrator)
    {
        $this->register('the-value', 33);
        $this->setHydrator($hydrator);
        $this->make(CreatableObject::class, '@the-value')
            ->shouldBeAnInstanceOf(CreatableObject::class);
        $hydrator->hydrate(Argument::any())->shouldHaveBeenCalled();
    }

    function it_creates_container_injection_implementations()
    {
        $this->register('some-value', new \stdClass());
        $this->make(CustomMethodObject::class)
            ->shouldBeAnInstanceOf(CustomMethodObject::class);

    }
}


class CreatableObject
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}

class CustomMethodObject implements ContainerInjectionInterface
{
    /**
     * @var
     */
    private $value;

    public function __construct(\stdClass $value)
    {
        $this->value = $value;
    }

    /**
     * Instantiates a new instance of this class.
     *
     * This is a factory method that returns a new instance of this class. The
     * factory should pass any needed dependencies into the constructor of this
     * class, but not the container itself. Every call to this method must return
     * a new instance of this class; that is, it may not implement a singleton.
     *
     * @param ContainerInterface $container
     *   The service container this instance should use.
     *
     * @return CustomMethodObject
     */
    public static function create(ContainerInterface $container)
    {
        return new static($container->get('some-value'));
    }
}