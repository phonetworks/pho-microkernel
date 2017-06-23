<?php

namespace Pho\Kernel\Standards;

use Pho\Framework;
use Pho\Kernel\Kernel;

class Founder extends Framework\Actor {

    use \Pho\Kernel\Bridge\NodeHydratorTrait;
    use \Pho\Kernel\Traits\Node\PersistentTrait;

    /**
     * u:: (myself) f -- can do anything
     * s:: (subscribers or friends) 7 -- read profile, send message, see friends
     * g:: (people in the same context) 5 -- read profile, ..., subscribe (and see friends because 4 is enabled)
     * o:: (people outside) 2 -- ..., ..., subscribe (read limited profile)
     */
    const DEFAULT_MODE = 0x0f751;

    /**
     * how owner can change the settings
     * f can't change sticky bit, must remain unchanged
     * f can't change his own settings, must remain intact
     * 8 can't give subscribers "manage" privilege, can do anything else
     * 8 can't give people in the same network "manage" privilege, can do anything else
     * 8 can't give outsiders "manage" privilege, can do anything else
     */
    // https://www.cyberciti.biz/tips/understanding-linux-unix-umask-value-usage.html
    const DEFAULT_MASK = 0xff888;

    public function __construct(Kernel $kernel)
    { 
        $this->graph = $kernel["space"];
        Framework\Actor::__construct($this->graph);
        $this->loadNodeTrait($kernel);
    }

}