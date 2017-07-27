<?php

namespace Pho\Kernel\Foundation;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Standards;

abstract class AbstractGraph extends Framework\Graph implements ParticleInterface {

    use ParticleTrait;

    public function __construct(Kernel $kernel, AbstractActor $actor, Framework\ContextInterface $graph)
    { 
        parent::__construct($actor, $graph);
        $this->hydrate($kernel, $graph);
        $this->kernel->space()->emit("particle.added", [$this]); 
    }

}