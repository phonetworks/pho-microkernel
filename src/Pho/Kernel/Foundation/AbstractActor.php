<?php

namespace Pho\Kernel\Foundation;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Standards;

abstract class AbstractActor extends Framework\Actor {

    use \Pho\Kernel\Bridge\NodeHydratorTrait;
    use ParticleTrait;

    public function __construct(Kernel $kernel, Framework\ContextInterface $graph)
    { 
        parent::__construct($graph);
        $this->particlize($kernel, $graph);
        
    }
}