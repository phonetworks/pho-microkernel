<?php

namespace Pho\Kernel\Standards;

use Pho\Framework;
use Pho\Kernel\Kernel;

class Graph extends \Pho\Framework\Graph {

    use \Pho\Kernel\Bridge\SubGraphHydratorTrait;
    use \Pho\Kernel\Traits\Node\PersistentTrait;

    public function __construct(Kernel $kernel, Framework\Actor $founder)
    { 
        parent::__construct($founder, $kernel["space"]);
        $founder->changeContext($this);
    }

}