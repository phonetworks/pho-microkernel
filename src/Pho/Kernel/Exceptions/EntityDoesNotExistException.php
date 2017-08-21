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
 * Thrown when given the uuid, there is no such node or edge (aka entity) 
 * stored in the database.
 *
 * @author Emre Sokullu
 */
class EntityDoesNotExistException extends \Exception {

    /**
     * Constructor
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct();
        $this->message = sprintf("There is no entity (node or edge) registered with the uuid %s", (string) $id);
    }

}
