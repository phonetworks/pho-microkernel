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
 * Thrown when there is no such pointer for given node.
 */
class NonExistentAclPointer extends \Exception {

    public function __construct(string $pointer, string $node_id) {
        parent::__construct();
        $this->message = sprintf("There is no permissions for the given pointer \"%s\" in the node \"%s\"", $pointer, $node_id);
    }
}