<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Slick\Di\Exception;

use RuntimeException;
use Slick\Di\Exception;

/**
 * Container Not Set Exception
 *
 * @package Slick\Di\Exception
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ContainerNotSetException extends RuntimeException implements Exception
{

}
