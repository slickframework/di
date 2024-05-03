<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\DefinitionLoader;

use ArrayObject;
use RecursiveDirectoryIterator;
use Slick\Di\DefinitionLoaderInterface;
use Slick\Di\Exception\InvalidDefinitionsPathException;
use Traversable;

/**
 * DirectoryDefinitionLoader
 *
 * @package Slick\Di\DefinitionLoader
 * @author  Filipe Silva <silvam.filipe@gmail.com>
*/
class DirectoryDefinitionLoader implements DefinitionLoaderInterface
{
    private array $definitions = [];

    /**
     * @throws \Exception
     */
    public function __construct(string $directory)
    {
        if (is_file($directory)) {
            $this->definitions = (array) (new FileDefinitionLoader($directory))->getIterator();
            return;
        }

        $this->loadDirectory($directory);
    }

    public function getIterator(): Traversable
    {
        return new ArrayObject($this->definitions);
    }

    /**
     * @param string $directory
     * @return void
     * @throws \Exception
     */
    public function loadDirectory(string $directory): void
    {
        try {
            $directory = new RecursiveDirectoryIterator($directory);
        } catch (\Exception $caught) {
            throw new InvalidDefinitionsPathException(
                'Provided definitions path is not valid or is not found. ' .
                'Could not create container. Please check ' . $directory
            );
        }

        $iterator = new \RecursiveIteratorIterator($directory);
        $phpFiles = new \RegexIterator($iterator, '/.*\.php$/i');

        foreach ($phpFiles as $phpFile) {
            $definitions = (array)(new FileDefinitionLoader($phpFile))->getIterator();
            $this->definitions = array_merge($this->definitions, $definitions);
        }
    }
}
