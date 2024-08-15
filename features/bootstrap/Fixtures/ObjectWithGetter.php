<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fixtures;

use Slick\Di\Definition\Attributes\Autowire;

/**
 * ObjectWithGetter
 *
 * @package Fixtures
 */
final class ObjectWithGetter
{

    private ?SomeObject $foo = null;

    private ?string $environment = 'prod';
    private ?string $test = null;

    public function foo(): ?SomeObject
    {
        return $this->foo;
    }

    #[Autowire]
    public function setFoo(SomeObject $foo): void
    {
        $this->foo = $foo;
    }

    public function environment(): ?string
    {
        return $this->environment;
    }

    #[Autowire('environment', 'foo.service')]
    public function setEnvironment(string $env, ?string $test = null): void
    {
        $this->test = $test;
        $this->environment = $env;
    }

    public function test(): ?string
    {
        return $this->test;
    }
}
