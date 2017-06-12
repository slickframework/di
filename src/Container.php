<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Interop\Container\Exception\ContainerException;
use Slick\Di\Definition\Alias;
use Slick\Di\Definition\Factory;
use Slick\Di\Definition\ObjectDefinition;
use Slick\Di\Definition\Scope;
use Slick\Di\Definition\Value;
use Slick\Di\Exception\NotFoundException;
use Slick\Di\Inspector\ConstructorArgumentInspector;

/**
 * Container
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Container implements ContainerInterface, ObjectHydratorAwareInterface
{
    /**
     * @var array
     */
    protected $definitions = [];

    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * @var ObjectHydratorInterface
     */
    protected $hydrator;

    /**
     * @var null|ContainerInterface
     */
    protected $parent;

    /**
     * Creates a dependency container
     */
    public function __construct()
    {
        $this->parent = array_key_exists('container', self::$instances)
            ? self::$instances['container']
            : null;

        self::$instances['container'] = $this;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (!$this->has($id) && $id !== 'container') {
            throw new NotFoundException(
                "Dependency container has not found any definition for '{$id}'"
            );
        }
        return $this->resolve($id);
    }

    /**
     * Returns true if the container can return an entry for the given
     * identifier. Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            return $this->parentHas($id);
        }

        return true;
    }

    /**
     * Adds a definition or a value to the container
     *
     * @param string       $name
     * @param mixed        $definition
     * @param Scope|string $scope      Resolving scope
     * @param array        $parameters Used if $value is a callable
     *
     * @return Container
     */
    public function register(
        $name,
        $definition = null,
        $scope = Scope::SINGLETON,
        array $parameters = []
    ) {
        if (!$definition instanceof DefinitionInterface) {
            $definition = $this->createDefinition(
                $definition,
                $parameters
            );
            $definition->setScope($scope);
        }
        return $this->add($name, $definition);
    }

    /**
     * Checks if parent has a provided key
     *
     * @param string $key
     *
     * @return bool
     */
    protected function parentHas($key)
    {
        if (!$this->parent) {
            return false;
        }
        return $this->parent->has($key);
    }

    /**
     * Resolves the definition that was saved under the provided name
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function resolve($name)
    {
        if (array_key_exists($name, self::$instances)) {
            return self::$instances[$name];
        }

        if (array_key_exists($name, $this->definitions)) {
            $entry = $this->definitions[$name];
            return $this->registerEntry($name, $entry);
        }

        return $this->parent->get($name);
    }

    /**
     * Checks the definition scope to register resolution result
     *
     * If scope is set to prototype the the resolution result is not
     * stores in the container instances.
     *
     * @param string              $name
     * @param DefinitionInterface $definition
     * @return mixed
     */
    protected function registerEntry($name, DefinitionInterface $definition)
    {
        $value = $definition
            ->setContainer($this->container())
            ->resolve();
        if ((string) $definition->getScope() !== Scope::PROTOTYPE) {
            self::$instances[$name] = $value;
        }
        return $value;
    }

    /**
     * Adds a definition to the definitions list
     *
     * This method does not override an existing entry if the same name exists
     * in the definitions or in any definitions of its parents.
     * This way it is possible to change entries defined by other packages
     * as those are build after the main application container is build.
     * The main application container should be the first to be created and
     * therefore set any entry that will override the latest containers build.
     *
     * @param string              $name
     * @param DefinitionInterface $definition
     *
     * @return Container
     */
    protected function add($name, DefinitionInterface $definition)
    {
        if ($this->has($name)) {
            return $this;
        }

        $this->definitions[$name] = $definition;
        $definition->setContainer($this->container());
        return $this;
    }

    /**
     * Creates the definition for registered data
     *
     * If value is a callable then the definition is Factory, otherwise
     * it will create a Value definition.
     *
     * @see Factory, Value
     *
     * @param callable|mixed $value
     * @param array          $parameters
     *
     * @return Factory|Value
     */
    protected function createDefinition(
        $value,
        array $parameters = []
    ) {
        if (is_callable($value)) {
            return new Factory($value, $parameters);
        }
        return $this->createValueDefinition($value);
    }

    /**
     * Creates a definition for provided name and value pair
     *
     * If $value is a string prefixed with '@' it will create an Alias
     * definition. Otherwise a Value definition will be created.
     *
     * @param mixed  $value
     *
     * @return Value|Alias
     */
    protected function createValueDefinition($value)
    {
        if (is_string($value) && strpos($value, '@') !== false) {
            return new Alias($value);
        }

        return new Value($value);
    }

    /**
     * Creates an instance of provided class injecting its dependencies
     *
     * @param string $className
     * @param array ...$arguments
     *
     * @return mixed
     */
    public function make($className, ...$arguments)
    {
        if (is_a($className, ContainerInjectionInterface::class, true)) {
            return call_user_func_array([$className, 'create'], [$this]);
        }

        $definition = (new ObjectDefinition($className))
            ->setContainer($this->container())
        ;

        $arguments = (new ConstructorArgumentInspector(
            new \ReflectionClass($className),
            $arguments
        ))
            ->arguments();

        call_user_func_array([$definition, 'with'], $arguments);
        $object = $definition->resolve();
        $this->getHydrator()->hydrate($object);
        return $object;
    }

    /**
     * Set the object hydrator
     *
     * @param ObjectHydratorInterface $hydrator
     *
     * @return Container|ObjectHydratorAwareInterface
     */
    public function setHydrator(ObjectHydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    /**
     * Get the object hydrator
     *
     * @return ObjectHydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->setHydrator(new ObjectHydrator($this));
        }
        return $this->hydrator;
    }

    /**
     * Gets the parent container if it exists
     *
     * @return null|ContainerInterface
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * Get the top container
     *
     * @return container
     */
    private function container()
    {
        return self::$instances['container'];
    }
}
