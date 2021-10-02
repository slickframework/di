<?php

namespace spec\Slick\Di\Definition\Object;

use PhpSpec\Exception\Example\FailureException;
use Slick\Di\ContainerInterface;
use Slick\Di\Definition\Object\DefinitionData;
use Slick\Di\Definition\Object\Resolver;
use Slick\Di\Definition\Object\ResolverInterface;
use PhpSpec\ObjectBehavior;

class ResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Resolver::class);
    }

    function its_a_resolver()
    {
        $this->shouldBeAnInstanceOf(ResolverInterface::class);
    }

    function it_resolves_simple_objects()
    {
        $data = new DefinitionData(SimpleObject::class);
        $this->resolve($data)->shouldBeAnInstanceOf(SimpleObject::class);
    }

    function it_resolvers_objects_with_constructor_dependencies(ContainerInterface $container)
    {
        $object = new SimpleObject();
        $container->get('first-object')->willReturn($object);
        $this->setContainer($container);
        $data = new DefinitionData(ConstructorObject::class, ['@first-object', 'test']);
        $this->resolve($data)->shouldBeAnInstanceOf(ConstructorObject::class);
    }

    function it_can_call_methods_with_arguments_on_resolved_objects(ContainerInterface $container, SimpleObject $object)
    {
        $container->get('first-object')->willReturn($object);
        $this->setContainer($container);
        $data = new DefinitionData(ConstructorObject::class, ['@first-object']);
        $data->addCall(DefinitionData::METHOD, 'setOther', ['@first-object']);
        $this->resolve($data)->shouldHavePropertyEquals('other', $object);
    }

    function it_can_assign_property_values_on_resolved_objects(ContainerInterface $container, SimpleObject $object)
    {
        $container->get('first-object')->willReturn($object);
        $this->setContainer($container);
        $data = new DefinitionData(ConstructorObject::class, ['@first-object']);
        $data->addCall(DefinitionData::PROPERTY, 'other', '@first-object');
        $this->resolve($data)->shouldHavePropertyEquals('other', $object);
    }

    function it_creates_object_from_classes_without_constructor()
    {
        $data = new DefinitionData(WithoutConstructor::class);
        $this->resolve($data)->shouldBeAnInstanceOf(WithoutConstructor::class);

    }

    public function getMatchers(): array
    {
        return [
            'havePropertyEquals' => function(ConstructorObject $subject, $property, $expected) {
                if ($expected == $subject->{$property}) {
                    return true;
                }
                throw new FailureException("Properties are not equal...");
            }
        ];
    }
}


class SimpleObject
{
}

class ConstructorObject
{
    /**
     * @var SimpleObject
     */
    private $object;

    /**
     * @var null
     */
    public $other;

    public function __construct(SimpleObject $object, $other = null)
    {
        $this->object = $object;
        $this->other = $other;
    }

    public function setOther($value)
    {
        $this->other = $value;
    }
}

class WithoutConstructor
{

}