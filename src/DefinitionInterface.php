<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Slick\Di\Definition\Scope;

/**
 * Definition Interface
 *
 * @package Slick\Di
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
interface DefinitionInterface extends ContainerAwareInterface
{
    /**
     * Resolves the definition into a scalar or object
     *
     * @return mixed
     */
    public function resolve();

    /**
     * Set resolution scope
     *
     * @param string|Scope $scope
     *
     * @return self|$this|DefinitionInterface
     */
    public function setScope($scope);

    /**
     * Get resolution scope
     *
     * @return Scope|string
     */
    public function getScope();
}
