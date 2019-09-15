<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\Server as SocketServer;

/**
 * Daemon for TCP connections to the kernel
 * 
 * Useful in testing real-time kernel setups.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Runner
{
    private $kernel;

    /**
     * @param Kernel $kernel
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Setup socket for main loop
     * @param  string $port
     * @param  string $host
     */
    public function listen($port, $host = '127.0.0.1')
    {
        $socket = new SocketServer("{$host}:{$port}", $this->kernel->loop);
        $socket->on('data', $this->app);
        
        echo("Kernel running on {$host}:{$port}\n");
        $this->kernel->loop->run();
    }

}