<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

/**
 * Object Hydrator Interface
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ObjectHydratorInterface extends ContainerAwareInterface
{

    /**
     * Used internal container to inject dependencies on provided object
     *
     * @param mixed $object
     *
     * @return self|ObjectHydratorInterface
     */
    public function hydrate($object);

}
