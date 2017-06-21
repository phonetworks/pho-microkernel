<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Events\Exceptions;

/**
 * Thrown when there's an attempt to emit an event that is not
 * registered.
 *
 * @author Emre Sokullu
 */
class InvalidEventException extends \Exception {

}
