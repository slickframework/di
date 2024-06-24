<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\DefinitionLoader;

use ArrayObject;
use Slick\Di\Definition\CreateDefinitionsMethods;
use Slick\Di\Definition\ObjectDefinition;
use Slick\Di\DefinitionLoaderInterface;
use Traversable;

/**
 * FileDefinitionLoader
 *
 * @package Slick\Di\DefinitionLoader
 * @author  Filipe Silva <silvam.filipe@gmail.com>
*/
class FileDefinitionLoader implements DefinitionLoaderInterface
{
    use CreateDefinitionsMethods;

    private array $definitions = [];

    public function __construct(string|array $definitions)
    {
        $services = is_string($definitions) ? require $definitions : $definitions;
        foreach ($services as $name => $definition) {
            $this->definitions[$name] = $definition instanceof ObjectDefinition
                ? $definition
                : $this->createDefinition($definition);
        }
    }


    public function getIterator(): Traversable
    {
        return new ArrayObject($this->definitions);
    }
}
