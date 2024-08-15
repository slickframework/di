<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition\Object;

/**
 * Resolver Aware Interface
 *
 * @package Slick\Di\Definition\Object
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ResolverAwareInterface
{

    /**
     * Set the object resolver
     *
     * @param ResolverInterface $resolver
     *
     * @return self|$this
     */
    public function setResolver(ResolverInterface $resolver);

    /**
     * Get the object resolver
     *
     * @return ResolverInterface
     */
    public function getResolver();
}
