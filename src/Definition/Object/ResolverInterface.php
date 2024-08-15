<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition\Object;

use Slick\Di\ContainerAwareInterface;

/**
 * Resolver Interface
 *
 * @package Slick\Di\Definition\Object
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ResolverInterface extends ContainerAwareInterface
{

    /**
     * Resolves the provided data into and object
     *
     * @param DefinitionData $data
     *
     * @return object
     */
    public function resolve(DefinitionData $data);
}
