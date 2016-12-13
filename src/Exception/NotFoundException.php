<?php

/**
 * This file is part of slick/di
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Exception;

use RuntimeException;
use Slick\Di\Exception;
use Interop\Container\Exception\NotFoundException as InteropException;

/**
 * NotFoundException
 *
 * @package Slick\Di\Exception
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class NotFoundException extends RuntimeException implements
    Exception,
    InteropException
{

}
