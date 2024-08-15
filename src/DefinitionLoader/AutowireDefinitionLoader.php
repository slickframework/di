<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di\DefinitionLoader;

use ArrayObject;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Slick\Di\Container;
use Slick\Di\ContainerAwareInterface;
use Slick\Di\ContainerInterface;
use Slick\Di\Definition\Factory;
use Slick\Di\DefinitionLoader\AutowireDefinitionLoader\ClassFile;
use Slick\Di\DefinitionLoaderInterface;
use Slick\Di\Exception\AmbiguousImplementationException;
use Slick\Di\Exception\InvalidDefinitionsPathException;
use Slick\FsWatch\Directory;
use Slick\FsWatch\Exception\DirectoryNotAccecible;
use Slick\FsWatch\Exception\DirectoryNotFound;
use SplFileInfo;
use Traversable;

/**
 * AutowireDefinitionLoader
 *
 * @package Slick\Di\DefinitionLoader
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AutowireDefinitionLoader implements DefinitionLoaderInterface, ContainerAwareInterface
{
    private ?ContainerInterface $container = null;

    private const TMP_FILE_NAME = '/_slick_di_autowire';

    /**
     * @var array<ClassFile>|ClassFile[]
     */
    private array $files = [];

    private array $definitions = [];

    protected array $implementations = [];

    private Directory $directoryWatcher;

    public function __construct(string $path)
    {
        try {
            $this->directoryWatcher = new Directory($path);
        } catch (DirectoryNotFound|DirectoryNotAccecible) {
            throw new InvalidDefinitionsPathException(
                'Provided autowire definitions path is not valid or is not found. ' .
                'Could not create container. Please check ' . $path
            );
        }

        if (!$this->loadImplementations()) {
            $this->loadFiles($path);
            $this->organiseImplementations();
        }

        $this->createDefinitions();
    }

    public function getIterator(): Traversable
    {
        return new ArrayObject($this->definitions);
    }

    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;
        return $this;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param string $path
     * @return void
     */
    public function loadFiles(string $path): void
    {
        try {
            $directory = new RecursiveDirectoryIterator($path);
        } catch (Exception) {
            throw new InvalidDefinitionsPathException(
                'Provided autowire definitions path is not valid or is not found. ' .
                'Could not create container. Please check ' . $path
            );
        }

        $iterator = new RecursiveIteratorIterator($directory);
        $phpFiles = new RegexIterator($iterator, '/.*\.php$/i');

        /** @var SplFileInfo $phpFile */
        foreach ($phpFiles as $phpFile) {
            $classFile = new ClassFile($phpFile->getPathname());
            if ($classFile->isAnImplementation()) {
                $this->files[] = $classFile;
            }
        }
    }

    /**
     * Organizes the implementations based on interfaces and parent classes.
     *
     * @return void
     */
    private function organiseImplementations(): void
    {
        foreach ($this->files as $file) {
            foreach ($file->interfaces() as $interface) {
                $entry = array_key_exists($interface, $this->implementations) ? $this->implementations[$interface] : [];
                if (in_array($file->className(), $entry)) {
                    continue;
                }
                $entry[] = $file->className();
                $this->implementations[$interface] = $entry;
            }

            if (!$file->parentClass()) {
                continue;
            }

            $entry = array_key_exists($file->parentClass(), $this->implementations)
                ? $this->implementations[$file->parentClass()]
                : [];

            if (!in_array($file->className(), $entry)) {
                $entry[] = $file->className();
                $this->implementations[$file->parentClass()] = $entry;
            }
        }
        $this->saveImplementations();
    }

    private function saveImplementations(): void
    {
        $file = sys_get_temp_dir() . self::TMP_FILE_NAME;
        if (is_file($file)) {
            unlink($file);
        }
        $data = [
            'snapshot' => $this->directoryWatcher->snapshot(),
            'implementations' => $this->implementations,
        ];
        file_put_contents($file, serialize($data));
    }

    private function loadImplementations(): bool
    {
        $file = sys_get_temp_dir() . self::TMP_FILE_NAME;
        if (!file_exists($file)) {
            return false;
        }

        $cachedData = unserialize(file_get_contents($file));

        /** @var Directory\Snapshot $snapshot */
        $snapshot = $cachedData['snapshot'];
        if ($this->directoryWatcher->hasChanged($snapshot)) {
            return false;
        }

        $this->implementations = $cachedData['implementations'];
        return true;
    }

    protected function createDefinitions():void
    {
        foreach ($this->implementations as $key => $classes) {
            if ($this->container && $this->container->has($key)) {
                continue;
            }
            $this->definitions[$key] = count($classes) === 1
                ? new Factory($this->createCallback($classes[0]))
                : new Factory($this->createAmbiguousCallback($key));
        }
    }

    private function createCallback(string $className): callable
    {
        return function (Container $container) use ($className) {
            return $container->make($className);
        };
    }

    private function createAmbiguousCallback(string $interface): callable
    {
        return function () use ($interface) {
            throw new AmbiguousImplementationException("Ambiguous implementation for '$interface'. " .
                "There are more then 1 implementations you need to provide a service definition for this interface.");
        };
    }
}
