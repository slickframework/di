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
use Slick\Di\Definition\Scope;
use Slick\Di\Definition\Value;
use Slick\Di\Exception\NotFoundException;

/**
 * Container
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Container implements ContainerInterface
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
        if (!$this->has($id)) {
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
        return array_key_exists($id, $this->definitions);
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
     * Resolves the definition that was saved under the provided name
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function resolve($name)
    {
        if (! array_key_exists($name, self::$instances)) {
            $entry = $this->definitions[$name];
            return $this->registerEntry($name, $entry);
        }
        return self::$instances[$name];
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
        $value = $definition->resolve();
        if ((string) $definition->getScope() !== Scope::PROTOTYPE) {
            self::$instances[$name] = $value;
        }
        return $value;
    }

    /**
     * Adds a definition to the definitions list
     *
     * @param string              $name
     * @param DefinitionInterface $definition
     *
     * @return Container
     */
    protected function add($name, DefinitionInterface $definition)
    {
        $this->definitions[$name] = $definition;
        $definition->setContainer($this);
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
}
