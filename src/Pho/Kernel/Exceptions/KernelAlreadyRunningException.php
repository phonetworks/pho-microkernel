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
 * Thrown when an outsider attempts to modify the state of the kernel
 * after it has booted up.
 *
 * @author Emre Sokullu
 */
class KernelAlreadyRunningException extends \Exception {

    /**
     * Constructor.
     *
     * @param string $additional_message
     */
    public function __construct(string $additional_message) {
        parent::__construct();
        $this->message = sprintf("The kernel is already running. %s", $additional_message);
    }

}
