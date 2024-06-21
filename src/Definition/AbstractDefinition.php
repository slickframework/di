<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Di\ContainerAwareMethods;
use Slick\Di\DefinitionInterface;

/**
 * Abstract Definition
 *
 * @package spec\Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractDefinition implements DefinitionInterface
{

    /**
     * @var Scope|string
     */
    protected $scope;

    /**
     * Implements the ContainerAwareInterface
     */
    use ContainerAwareMethods;

    /**
     * Set resolution scope
     *
     * @param string|Scope $scope
     *
     * @return self|$this|AbstractDefinition
     */
    public function setScope($scope): DefinitionInterface|static
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * Get resolution scope
     *
     * @return Scope|string
     */
    public function getScope(): string|Scope
    {
        if (!$this->scope) {
            return Scope::Singleton();
        }
        return $this->scope;
    }
}
