<?php

namespace Pho\Kernel\Standards;

use Pho\Framework;
use Pho\Kernel\Kernel;

class Founder extends Framework\Actor {

    use \Pho\Kernel\Bridge\NodeHydratorTrait;
    use \Pho\Kernel\Traits\Node\PersistentTrait;

    public function __construct(Kernel $kernel)
    { 
        $this->graph = $kernel["space"];
        Framework\Actor::__construct($this->graph);
        $this->loadNodeTrait($kernel);
    }

}