<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use ReflectionClass;
use ReflectionException;
use Slick\Di\Definition\CreateDefinitionsMethods;
use Slick\Di\Definition\ObjectDefinition;
use Slick\Di\Definition\Scope;
use Slick\Di\Exception\NotFoundException;
use Slick\Di\Inspector\ConstructorArgumentInspector;

/**
 * Container
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Container implements ContainerInterface
{
    use CreateDefinitionsMethods;

    /**
     * @var array
     */
    protected array $definitions = [];

    /**
     * @var array
     */
    protected static array $instances = [];

    /**
     * @var null|ContainerInterface
     */
    protected mixed $parent;

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
     * @return mixed Entry.
     *@throws NotFoundException  No entry was found for this identifier.
     *
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id) && $id !== 'container') {
            if (class_exists($id)) {
                $reflectionClass = new ReflectionClass($id);
                if ($reflectionClass->isInstantiable()) {
                    return $this->make($id);
                }
            }
            throw new NotFoundException(
                "Dependency container has not found any definition for '$id'"
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
    public function has(string $id): bool
    {
        if (!array_key_exists($id, $this->definitions)) {
            return $this->parentHas($id);
        }

        return true;
    }

    /**
     * Adds a definition or a value to the container
     *
     * @param string $name
     * @param mixed|null $definition
     * @param string|Scope $scope      Resolving scope
     * @param array        $parameters Used if $value is a callable
     *
     * @return Container
     */
    public function register(
        string       $name,
        mixed        $definition = null,
        string|Scope $scope = Scope::SINGLETON,
        array        $parameters = []
    ): Container {
        if ($definition instanceof DefinitionInterface) {
            return $this->add($name, $definition);
        }

        $definition = $this->createDefinition(
            $definition,
            $parameters
        );
        $scope = is_string($scope) ? new Scope($scope) : $scope;
        $definition->setScope($scope);
        return $this->add($name, $definition);
    }

    /**
     * Checks if parent has a provided key
     *
     * @param string $key
     *
     * @return bool
     */
    protected function parentHas(string $key): bool
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
     *
     * @throws NotFoundException
     */
    protected function resolve(string $name): mixed
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
     * If scope is set to prototype the resolution result is not
     * stores in the container instances.
     *
     * @param string $name
     * @param DefinitionInterface $definition
     * @return mixed
     */
    protected function registerEntry(string $name, DefinitionInterface $definition): mixed
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
     * @param string $name
     * @param DefinitionInterface $definition
     *
     * @return Container
     */
    protected function add(string $name, DefinitionInterface $definition): static
    {
        if ($this->has($name)) {
            return $this;
        }

        $this->definitions[$name] = $definition;
        $definition->setContainer($this->container());
        return $this;
    }

    /**
     * Creates an instance of provided class injecting its dependencies
     *
     * @param string $className
     * @param array ...$arguments
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function make(string $className, ...$arguments): mixed
    {
        if (is_a($className, ContainerInjectionInterface::class, true)) {
            return call_user_func_array([$className, 'create'], [$this]);
        }

        return $this->createFromClass($className, $this->container(), ...$arguments)->resolve();
    }

    /**
     * Gets the parent container if it exists
     *
     * @return null|ContainerInterface
     */
    public function parent(): ?ContainerInterface
    {
        return $this->parent;
    }

    /**
     * Get the top container
     *
     * @return container
     */
    private function container(): Container
    {
        return self::$instances['container'];
    }
}
