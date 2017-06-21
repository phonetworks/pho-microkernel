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
 * Thrown when there is an invalid permission unit in serialized ACL.
 */
class InvalidSerializedAclUnitException extends \Exception {

    public function __construct(string $permission, array $permissions, string $node) {
        parent::__construct();
        $this->message = sprintf("The serialized ACL unit \"%s\" in the set \"%s\" was invalid with the node \"%s\"", $permission, print_r($permissions, true), $node);
    }
}