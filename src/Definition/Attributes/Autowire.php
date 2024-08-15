<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\Di\Definition\Attributes;

use Attribute;

/**
 * Autowire
 *
 * @package Slick\Di\Definition\Attributes
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Autowire
{
    private array $services = [];

    public function __construct(...$services)
    {
        foreach ($services as $service) {
            $this->services[] = "@$service";
        }
    }

    public function services(): array
    {
        return $this->services;
    }
}
