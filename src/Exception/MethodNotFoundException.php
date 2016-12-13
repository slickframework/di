<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Exception;

use InvalidArgumentException;
use Slick\Di\Exception;

/**
 * Method Not Found Exception
 *
 * @package Slick\Di\Exception
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class MethodNotFoundException extends InvalidArgumentException implements Exception
{

}
