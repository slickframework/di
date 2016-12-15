<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Interop\Container\ContainerInterface as InteropContainer;

use Slick\Common\Inspector;

/**
 * ObjectHydrator
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
*/
class ObjectHydrator implements ObjectHydratorInterface
{

    /**
     * @var Inspector
     */
    private $inspector;

    /**
     * @var mixed
     */
    private $object;

    /**
     * Methods to implement the ContainerAwareInterface
     */
    use ContainerAwareMethods;

    /**
     * Creates an Object Hydrator
     *
     * @param InteropContainer $container
     */
    public function __construct(InteropContainer $container)
    {
        $this->setContainer($container);
    }

    /**
     * Used internal container to inject dependencies on provided object
     *
     * @param mixed $object
     *
     * @return ObjectHydrator|ObjectHydratorInterface
     */
    public function hydrate($object)
    {
        $this->inspector = Inspector::forClass($object);
        $this->object = $object;
        foreach ($this->inspector->getClassMethods() as $method) {
            $this->evaluate($method);
        }
        return $this;
    }

    /**
     * Evaluates method annotations and injects the dependency if needed
     *
     * @param string $method
     */
    protected function evaluate($method)
    {
        $annotations = $this->inspector->getMethodAnnotations($method);

        if (! $annotations->hasAnnotation('@inject')) {
            return;
        }

        $annotation = $annotations->getAnnotation('@inject');
        $param = $annotation->getValue();

        $this->inspector
            ->getReflection()
            ->getMethod($method)
            ->invokeArgs($this->object, [$this->getContainer()->get($param)])
        ;
    }
}
