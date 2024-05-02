<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Behat\Behat\Tester\Exception\PendingException;
use Psr\Container\ContainerExceptionInterface;
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
     * @var \Slick\Di\DefinitionInterface|\Slick\Di\Definition\ObjectDefinition
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
     * @When /^I try to get "([^"]*)" from container$/
     */
    public function iTryGetFromContainer($definition)
    {
        $this->lastValue = $this->container->get($definition);
    }

    /**
     * @Then /^the value should be "([^"]*)"$/
     *
     * @throws \Exception
     */
    public function theValueShouldBe($expected)
    {
        if ($expected === $this->lastValue) {
            return;
        }

        throw new \Exception("Expecting $expected, but got {$this->lastValue}");
    }

    /**
     * @Then /^I should get an exception$/
     * @throws \Exception
     */
    public function iShouldGetAnException()
    {

        if (is_subclass_of($this->lastException, Exception::class))
        {
            return;
        }

        throw new \Exception("No exception was thrown...");
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
     * @throws \Exception
     */
    public function theValueShouldBeAnObject()
    {
        if ($this->lastValue instanceof stdClass) {
            return;
        }

        throw new \Exception("The expected value is not an object...");
    }

    /**
     * @Then /^it should be the same as "([^"]*)"$/
     *
     * @throws ContainerExceptionInterface
     * @throws \Exception
     */
    public function itShouldBeTheSameAs($alias)
    {
        $new = $this->container->get($alias);

        if ($new === $this->lastValue) {
            return;
        }

        throw new \Exception("Objects are note the same...");
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
     * @throws ContainerExceptionInterface
     * @throws \Exception
     */
    public function itShouldBeTheEqualTo($key)
    {

        if ($this->lastValue == $this->container->get($key)) {
            return;
        }

        throw new \Exception("Objects are not equal...");
    }

    /**
     * @Given /^it should not be the same as "([^"]*)"$/
     * @throws \Exception
     * @throws ContainerExceptionInterface
     */
    public function itShouldNotBeTheSameAs($key)
    {
        if ($this->lastValue === $this->container->get($key))

            throw new \Exception("Objects are the same...");
    }

    /**
     * @Given /^I create an object definition for class "([^"]*)"$/
     */
    public function iCreateAnObjectDefinition($className)
    {
        $this->definition = new \Slick\Di\Definition\ObjectDefinition($className);
    }

    /**
     * @Then /^it should be an instance of "([^"]*)"$/
     * @throws \Exception
     */
    public function itShouldBeAnInstanceOfFixturesObject($className)
    {
        if ($this->lastValue instanceof $className) {
            return;
        }

        throw new \Exception("It is not an instance of $className");
    }

    /**
     * @Given /^I create an object definition for class "([^"]*)" with:$/
     */
    public function iCreateAnObjectDefinitionWith($className, TableNode $arguments)
    {
        $this->definition = new \Slick\Di\Definition\ObjectDefinition($className);
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
     * @throws \Exception
     */
    public function itShouldHaveAPropertyEqualsTo($property, $value)
    {
        if ($value === $this->lastValue->{$property}) return;

        throw new \Exception("There are no properties with provided value...");
    }

    /**
     * @Given /^I assign "([^"]*)" to property "([^"]*)"$/
     */
    public function iAssignToProperty($value, $property)
    {
        $this->definition->assign($value)->to($property);
    }

    /**
     * @When /^I use the container to create "([^"]*)"$/
     */
    public function iUseContainerToCreate($className)
    {
        $this->iUseContainerToCreateWith($className);
    }

    /**
     * @When /^I use the container to create "([^"]*)" width:$/
     *
     * @param $className
     * @param TableNode $arguments
     */
    public function iUseContainerToCreateWith($className, TableNode $arguments = null)
    {
        if (is_null($arguments)) {
            $this->lastValue = $this->container->make($className);
            return;
        }

        $args = [$className];
        foreach ($arguments->getRow(0) as $value) {
            $args[] = $value;
        }

        $this->lastValue = call_user_func_array([$this->container, 'make'], $args);
    }

    /**
     * @Given /^I build a container with "([^"]*)"$/
     */
    public function iBuildAContainerWith($file)
    {
        $file =  __DIR__ . "/{$file}";
        $this->container = (new \Slick\Di\ContainerBuilder($file))->getContainer();
    }

    /**
     * @Then exception :name should be thrown
     */
    public function exceptionShouldBeThrown(string $name): void
    {
        if (!$this->lastException) {
            throw new \Exception("No exception was thrown...");
        }

        if (!($this->lastException instanceof $name)) {
            $type = get_class($this->lastException);
            throw new \Exception("Unexpected exception type... Type caught was '$type'");
        }
    }
}
