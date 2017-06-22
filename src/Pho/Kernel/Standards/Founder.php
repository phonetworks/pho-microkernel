<?php

namespace Pho\Kernel\Standards;

use Pho\Framework;
use Pho\Kernel\Kernel;

class Founder extends Framework\Actor {

    public function __construct(Kernel $kernel)
    { 
        $this->graph = $kernel["space"];
        Framework\Actor::__construct($this->graph);
        $this->loadNodeTrait($kernel);
    }

}