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
 * Thrown if at initial boot, when the graph or the founder
 * have not been set, the kernel is not provided with a 
 * founder manually.
 *
 * @author Emre Sokullu
 */
class FounderMustBeSetException extends \Exception {

    /**
     * Constructor
     *
     * @param string $id
     */
    public function __construct()
    {
        parent::__construct();
        $this->message = "A Founder actor object must be provided at initial boot time.";
    }

}
