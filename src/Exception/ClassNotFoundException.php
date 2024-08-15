<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di\Exception;

use InvalidArgumentException;

use Slick\Di\Exception;

/**
 * Class Not Found Exception
 *
 * @package Slick\Di\Exception
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class ClassNotFoundException extends InvalidArgumentException implements
    Exception
{

}
