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
 * Thrown when an adapter does not cover the given method.
 *
 * @author Emre Sokullu
 */
class UnimplementedMethodException extends \Exception {
  public function __construct() {
    $message = sprintf("Unimplemented Method: %s", __METHOD__);
    Kernel::service("logger")->warning($message);
    $this->message = $message;
  }
}
