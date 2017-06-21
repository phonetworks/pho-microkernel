<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Exceptions;

/**
 * Thrown when given the uuid, the corresponding an element is not a node
 * that implements the standard NodeInterface. This exception extends
 * NodeDoesNotExistException
 *
 * @author Emre Sokullu
 */
class NotANodeException extends NodeDoesNotExistException {

}
