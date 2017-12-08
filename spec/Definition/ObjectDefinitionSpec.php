<?php

namespace spec\Slick\Di\Definition;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Slick\Di\ContainerInterface;
use Slick\Di\Definition\FluentObjectDefinitionInterface;
use Slick\Di\Definition\Object\DefinitionData;
use Slick\Di\Definition\Object\ResolverInterface;
use Slick\Di\Definition\ObjectDefinition;
use Slick\Di\DefinitionInterface;
use Slick\Di\Exception\ClassNotFoundException;
use Slick\Di\Exception\MethodNotFoundException;
use Slick\Di\Exception\PropertyNotFoundException;

class ObjectDefinitionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(InitializableService::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ObjectDefinition::class);
    }

    function its_a_definition()
    {
        $this->shouldBeAnInstanceOf(DefinitionInterface::class);
    }

    function its_a_fluent_object_definition()
    {
        $this->shouldBeAnInstanceOf(FluentObjectDefinitionInterface::class);
    }

    function it_resolves_objects_using_an_object_resolver_interface(
        ResolverInterface $resolver,
        ContainerInterface $container
    ) {
        $resolver->setContainer($container)->willReturn($resolver);
        $resolver->resolve(new DefinitionData(InitializableService::class))->shouldBeCalled();
        $this->setResolver($resolver);
        $this->setContainer($container);
        $this->resolve();
        $resolver->setContainer($container)->shouldHaveBeenCalled();
    }

    function it_throws_class_not_found_exception_if_class_name_cannot_be_loaded()
    {
        $this->shouldThrow(ClassNotFoundException::class)
            ->during('__construct', ['some\class\Path']);
    }

    function it_can_define_constructor_arguments()
    {
        $this->with('hello', 'world');
        $this->getDefinitionData()->shouldHaveArgumentsEquals(['hello', 'world']);
    }

    function it_can_define_constructor_arguments_with_old_method()
    {
        $this->setConstructArgs(['hello', 'world']);
        $this->getDefinitionData()->shouldHaveArgumentsEquals(['hello', 'world']);
    }

    function it_can_define_a_method_call_with_arguments()
    {
        $this->call('doSomething')->with('hello', 'world');
        $this->getDefinitionData()->shouldHaveACallEquals(
            [
                'type' => DefinitionData::METHOD,
                'name' => 'doSomething',
                'arguments' => ['hello', 'world']
            ]

        );
    }

    function it_can_define_a_method_call_with_arguments_using_old_method()
    {
        $this->setMethod('doSomething', ['hello', 'world']);
        $this->getDefinitionData()->shouldHaveACallEquals(
            [
                'type' => DefinitionData::METHOD,
                'name' => 'doSomething',
                'arguments' => ['hello', 'world']
            ]
        );
    }

    function it_throws_method_not_found_exception_if_defining_an_undefined_method_call()
    {
        $this->beConstructedWith(InitializableService::class);
        $this->shouldThrow(MethodNotFoundException::class)
            ->during('call', ['test']);
    }

    function it_can_define_an_assignment_to_a_property()
    {
        $this->assign('test')->to('scope');
        $this->getDefinitionData()->shouldHaveACallEquals(
            [
                'type' => DefinitionData::PROPERTY,
                'name' => 'scope',
                'arguments' => 'test'
            ]
        );
    }

    function it_can_define_an_assignment_to_a_property_with_old_method()
    {
        $this->setProperty('scope', 'test');
        $this->getDefinitionData()->shouldHaveACallEquals(
            [
                'type' => DefinitionData::PROPERTY,
                'name' => 'scope',
                'arguments' => 'test'
            ]
        );
    }

    function it_throws_property_not_found_exception_if_defining_an_undefined_property_call()
    {
        $this->beConstructedWith(InitializableService::class);
        $this->shouldThrow(PropertyNotFoundException::class)
            ->during('to', ['test']);
    }

    function it_can_chain_multiple_method_calls()
    {
        $this->call('doSomething')->with('hello1');
        $this->call('doSomething')->with('hello2');
        $this->call('doSomething')->with('hello3');
        $this->getDefinitionData()->shouldHaveACallEquals(
            [
                'type' => DefinitionData::METHOD,
                'name' => 'doSomething',
                'arguments' => ['hello1']
            ]

        );
    }

    public function getMatchers()
    {
        return [
            'haveArgumentsEquals' => function (DefinitionData $subject, $arguments) {
                 return $subject->arguments() == $arguments;
            },
            'haveACallEquals' => function (DefinitionData $subject, $expectedCall) {
                $call = null;
                foreach ($subject->calls() as $call) {
                    if ($call == $expectedCall) {
                        return true;
                    }
                }
                $message = "should have an expected {$expectedCall['type']} call, but it hasn't. Check it out:";
                try {
                    \PHPUnit_Framework_Assert::assertEquals($expectedCall, $call);
                } catch (\PHPUnit_Framework_ExpectationFailedException $caught) {
                    $message .=  "\n". $caught->getComparisonFailure()->getDiff();

                }

                throw new FailureException($message);
            }
        ];

    }
}

class InitializableService
{

    public $scope = null;

    function doSomething()
    {

    }
}
