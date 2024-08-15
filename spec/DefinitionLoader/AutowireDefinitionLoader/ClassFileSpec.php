<?php
/**
 * This file is part of di
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace spec\Slick\Di\DefinitionLoader\AutowireDefinitionLoader;

use Slick\Di\DefinitionLoader\AutowireDefinitionLoader\ClassFile;
use PhpSpec\ObjectBehavior;

class ClassFileSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(__DIR__ . '/Fixtures/SomeClass.php');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ClassFile::class);
    }

    function its_a_class()
    {
        $this->isAClass()->shouldBe(true);
    }

    function it_has_a_class_name()
    {
        $this->className()->shouldBe('DefinitionLoader\AutowireDefinitionLoader\Fixtures\SomeClass');
    }

    function it_has_a_parent_class_name()
    {
        $this->parentClass()->shouldBe('Behat\Testwork\Event\Event');
    }

    function it_has_a_list_of_interfaces()
    {
        $this->interfaces()->shouldBe([
            'Symfony\Component\EventDispatcher\EventSubscriberInterface',
            '\Stringable'
        ]);
    }

    function it_can_check_if_its_an_implememtation()
    {
        $this->isAnImplementation()->shouldBe(true);
    }
}
