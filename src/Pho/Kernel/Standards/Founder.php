<?php

namespace Pho\Kernel\Standards;

use Pho\Framework;
use Pho\Kernel\Kernel;

class Founder extends User {

    public function __construct(Kernel $kernel)
    { 
        $this->graph = $kernel["universe"];
        Framework\Actor::__construct($this->graph);
        $this->loadNodeTrait($kernel);
    }

}