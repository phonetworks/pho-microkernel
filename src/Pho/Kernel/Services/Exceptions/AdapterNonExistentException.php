<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 namespace Pho\Kernel\Services\Exceptions;

/**
 * Exception thrown when the requested adapter can't be found
 * in the standard adapters directory, or is not registered.
 *
 * @author Emre Sokullu
 */

 class AdapterNonExistentException extends \Exception {

 }
