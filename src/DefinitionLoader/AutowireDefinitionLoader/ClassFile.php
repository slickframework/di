<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\Di\DefinitionLoader\AutowireDefinitionLoader;

/**
 * ClassFile
 *
 * @package Slick\Di\DefinitionLoader\AutowireDefinitionLoader
 */
final class ClassFile
{
    private string $content;
    private ?string $namespace = null;
    private ?string $className = null;
    private array $interfaces = [];

    private ?string $parentClass = null;

    /**
     * Creates a ClassFile object
     *
     * @param string $file The path of the file to be read.
     */
    public function __construct(string $file)
    {
        $this->content = file_get_contents($file);
        $this->parse();
    }

    /**
     * Is this PHP file a class
     *
     * @return bool
     */
    public function isAClass(): bool
    {
        return is_scalar($this->className);
    }

    /**
     * Class namespace
     *
     * @return string|null
     */
    public function namespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * FQCN class name
     *
     * @return string|null
     */
    public function className(): ?string
    {
        return $this->className;
    }

    /**
     * List of FQCN for the implemented interfaces
     *
     * @return array
     */
    public function interfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * FQCN of the parent class
     *
     * @return string|null
     */
    public function parentClass(): ?string
    {
        return $this->parentClass;
    }

    /**
     * Parses file contents
     *
     * @return void
     */
    private function parse(): void
    {
        $regex = '/class (?<name>\w*)(\sextends\s(?<parent>\w*))?(\simplements\s(?<interfaces>[\w\,\s\\\]*)\s)/i';
        if (preg_match($regex, $this->content, $matches) === false) {
            return;
        }

        if (!isset($matches['name'])) {
            return;
        }

        $namespaceRegEx = '/namespace(?<namespace>(.*));/i';
        preg_match($namespaceRegEx, $this->content, $found);

        $this->namespace = trim($found['namespace']);
        $this->className = $this->namespace . "\\" . trim($matches['name']) ?? null;

        $interfaces = $this->clearNames($matches['interfaces'] ? explode(',', $matches['interfaces']) : []);
        $parentClass = $matches['parent'] ?? null;
        $this->parseImports($interfaces, $parentClass);
    }

    /**
     * Parses "use" statements to complete the parent class and interfaces FQCN
     *
     * @param array $interfaceNames
     * @param string|null $parentClass
     * @return void
     */
    private function parseImports(array $interfaceNames, ?string $parentClass = null): void
    {
        $usesRegex = '/use(?<uses>(.*));/i';
        preg_match_all($usesRegex, $this->content, $matches);
        $uses = empty($matches['uses']) ? [] : $matches['uses'];

        $this->interfaces = $this->pairInterfaces($interfaceNames, $uses);
        $this->parentClass = $parentClass ? $this->pairParentClass($uses, $parentClass) : null;
    }

    /**
     * Clears the names in the given array
     *
     * @param array $param The array containing names to be cleared.
     * @return array The array with cleared names.
     */
    private function clearNames(array $param): array
    {
        $clean = [];
        foreach ($param as $name) {
            $clean[] = trim(str_replace(',', '', $name));
        }
        return $clean;
    }

    /**
     * Filter and pair interface names with their corresponding uses
     *
     * @param array $interfaceNames The array of interface names
     * @param array $uses The array of use statements
     *
     * @return array The array of paired interface names with their corresponding uses
     */
    private function pairInterfaces(array $interfaceNames, array $uses): array
    {
        $interfaces = [];
        foreach ($interfaceNames as $interfaceName) {
            $found = null;
            foreach ($uses as $use) {
                if (str_contains($use, $interfaceName)) {
                    $found = trim($use);
                    break;
                }
            }
            if ($found) {
                $interfaces[] = $found;
                continue;
            }

            $interfaces[] = str_starts_with($interfaceName, "\\")
                ? $interfaceName
                : $this->namespace . "\\" . $interfaceName;
        }
        return $interfaces;
    }

    /**
     * Pair the parent class with the corresponding use statement
     *
     * @param array  $uses        An array of use statements
     * @param string $parentClass The parent class name
     *
     * @return string The fully qualified name of the parent class with the corresponding
     *                use statement or null if not found
     */
    private function pairParentClass(array $uses, string $parentClass): string
    {
        foreach ($uses as $use) {
            if (str_contains($use, $parentClass)) {
                return trim($use);
            }
        }
        return str_starts_with(trim($parentClass), "\\")
            ? trim($parentClass)
            : $this->namespace . "\\" . trim($parentClass);
    }

    /**
     * Checks if the class is an implementation by
     * verifying if the parent class or interfaces
     * are present.
     *
     * @return bool
     */
    public function isAnImplementation(): bool
    {
        return $this->isAClass() && !is_null($this->parentClass) || !empty($this->interfaces);
    }
}
