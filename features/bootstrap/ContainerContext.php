<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Slick\Di\Container;
use Slick\Di\Exception;
use Behat\Gherkin\Node\TableNode;

/**
 * ContainerContext
 *
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class ContainerContext extends FeatureContext
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var mixed
     */
    protected $lastValue;

    /**
     * @var null|Exception
     */
    protected $lastException;

    /**
     * @var \Slick\Di\DefinitionInterface|\Slick\Di\Definition\Object
     */
    protected $definition;

    /**
     * @var callable
     */
    protected $callable;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @Given /^I create a container$/
     */
    public function iCreateAContainer()
    {
        $this->container = new Container();
    }

    /**
     * @Given /^register a "([^"]*)" under "([^"]*)" key$/
     */
    public function registerAUnderKey($value, $definition)
    {
        $this->container->register($definition, $value);
    }

    /**
     * @When /^I get "([^"]*)" from container$/
     */
    public function iGetFromContainer($definition)
    {
        $this->lastException = null;
        try {
            $this->lastValue = $this->container->get($definition);
        } catch (Exception $caught) {
            $this->lastException = $caught;
        }
    }

    /**
     * @Then /^the value should be "([^"]*)"$/
     */
    public function theValueShouldBe($expected)
    {
        PHPUnit_Framework_Assert::assertEquals($expected, $this->lastValue);
    }

    /**
     * @Then /^I should get an exception$/
     */
    public function iShouldGetAnException()
    {
        PHPUnit_Framework_Assert::assertInstanceOf(
            Exception::class,
            $this->lastException
        );
    }

    /**
     * @Given /^I define a callable that returns an object$/
     */
    public function iDefineACallableThatReturnsAnObject()
    {
        $this->callable = function () {
            return (object)[];
        };
        $this->definition = $this->callable;
    }

    /**
     * @Given /^register it under "([^"]*)" key$/
     */
    public function registerItUnderKey($name)
    {
        $this->lastName = $name;
        $this->container->register($name, $this->definition);
    }

    /**
     * @Then /^the value should be an object$/
     */
    public function theValueShouldBeAnObject()
    {
        PHPUnit_Framework_Assert::assertInstanceOf(
            stdClass::class,
            $this->lastValue
        );
    }

    /**
     * @Then /^it should be the same as "([^"]*)"$/
     */
    public function itShouldBeTheSameAs($alias)
    {
        $new = $this->container->get($alias);
        PHPUnit_Framework_Assert::assertSame($this->lastValue, $new);
    }

    /**
     * @Given /^register it under "([^"]*)" key with "([^"]*)" scope$/
     */
    public function registerItUnderKeyWithScope($key, $scope)
    {
        $this->lastName = $key;
        $this->container->register($key, $this->callable, $scope);
    }

    /**
     * @Then /^it should be the equal to "([^"]*)"$/
     */
    public function itShouldBeTheEqualTo($key)
    {
        PHPUnit_Framework_Assert::assertEquals(
            $this->lastValue,
            $this->container->get($key)
        );
    }

    /**
     * @Given /^it should not be the same as "([^"]*)"$/
     */
    public function itShouldNotBeTheSameAs($key)
    {
        PHPUnit_Framework_Assert::assertNotSame(
            $this->lastValue,
            $this->container->get($key)
        );
    }

    /**
     * @Given /^I create an object definition for class "([^"]*)"$/
     */
    public function iCreateAnObjectDefinition($className)
    {
        $this->definition = new \Slick\Di\Definition\Object($className);
    }

    /**
     * @Then /^it should be an instance of "([^"]*)"$/
     */
    public function itShouldBeAnInstanceOfFixturesObject($className)
    {
        PHPUnit_Framework_Assert::assertInstanceOf($className, $this->lastValue);
    }

    /**
     * @Given /^I create an object definition for class "([^"]*)" with:$/
     */
    public function iCreateAnObjectDefinitionWith($className, TableNode $arguments)
    {
        $this->definition = new \Slick\Di\Definition\Object($className);
        list($arg1, $arg2) = $arguments->getRow(0);
        $this->definition->withConstructorArgument($arg1, $arg2);
    }

    /**
     * @Given /^I add a call to method "([^"]*)" with "([^"]*)"$/
     */
    public function iAddACallToMethodWith($method, $argument)
    {
        $this->definition->call($method)->with($argument);
    }

    /**
     * @Given /^it should have a property "([^"]*)" equals to "([^"]*)"$/
     */
    public function itShouldHaveAPropertyEqualsTo($property, $value)
    {
        PHPUnit_Framework_Assert::assertEquals($value, $this->lastValue->{$property});
    }

    /**
     * @Given /^I assign "([^"]*)" to property "([^"]*)"$/
     */
    public function iAssignToProperty($value, $property)
    {
        $this->definition->assign($value)->to($property);
    }
}
