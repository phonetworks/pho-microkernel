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
 * Thrown when the creator of the network can't be found.
 *
 * @author Emre Sokullu
 */
class LostCreatorException extends \Exception {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->message = "The network creator cannot be found.";
    }

}
