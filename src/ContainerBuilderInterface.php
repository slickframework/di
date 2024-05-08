<?php

/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\Di;

use Slick\Di\ContainerAwareInterface;

/**
 * ContainerBuilderInterface
 *
 * @package Slick\Di
 */
interface ContainerBuilderInterface extends ContainerAwareInterface
{

    /**
     * Loads the definitions of a given loader
     *
     * @param DefinitionLoaderInterface $loader
     * @return self
     * @throws Exception
     */
    public function load(DefinitionLoaderInterface $loader): ContainerBuilder;
}
