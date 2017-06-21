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
class InvalidAclPointerException extends \Exception {

    public function __construct(string $entity_pointer, string $valid_pointer, string $function) {
        parent::__construct();
        $this->message = sprintf("The entity pointer %s is not valid. The valid pointer for the method %s is: %s", $entity_pointer, $function, $valid_pointer);
    }
}