<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

/**
 * Object Hydrator Aware Interface
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ObjectHydratorAwareInterface
{

    /**
     * Set the object hydrator
     *
     * @param ObjectHydratorInterface $hydrator
     *
     * @return self|ObjectHydratorAwareInterface
     */
    public function setHydrator(ObjectHydratorInterface $hydrator);

    /**
     * Get the object hydrator
     *
     * @return ObjectHydratorInterface
     */
    public function getHydrator();
}
