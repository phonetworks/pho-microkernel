<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Acl\Exceptions;

/**
 * Thrown with an attempt to set an object's Acl with invalid mode.
 */
class InvalidAclModeException extends \Exception {
    public function __construct(string $mode) {
        parent::__construct();
        $this->message = sprintf("%s is not a valid ACL mode", $mode);
    }
}