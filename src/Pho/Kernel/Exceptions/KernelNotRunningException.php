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
 * Thrown when an outsider attempts to access kernel while it has not
 * booted up yet.
 *
 * @author Emre Sokullu
 */
class KernelNotRunningException extends \Exception {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->message = "The kernel is not running";
    }

}
