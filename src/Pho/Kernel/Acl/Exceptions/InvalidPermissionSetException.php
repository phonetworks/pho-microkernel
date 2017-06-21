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
 * Thrown when the permission set is neither hexadecimal nor an array of permission sets.
 */
class InvalidPermissionSetException extends \Exception {

    public function __construct(/*mixed*/ $permission, string $node) {
        parent::__construct();
        $this->message = sprintf("Invalid serialized ACL  (a \"%s\") \"%s\" with the node \"%s\"", gettype($permission), print_r($permission, true), $node);
    }
}