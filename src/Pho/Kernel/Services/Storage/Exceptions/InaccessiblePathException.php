<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Storage\Exceptions;

/**
 * Thrown when the given path does not exist and can't create one.
 *
 * @author Emre Sokullu
 */
class InaccessiblePathException extends \Exception {

  public function __construct(string $path) {
    $this->message = sprintf("Inaccessible path. Check for permissions on: %s", $path);
  }

}
