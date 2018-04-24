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
        $this->registerSetHandler();
        $this->hydrate($kernel, $graph);
        $this->kernel->space()->emit("particle.added", [$this]); 
        $this->listenToMemberChanges();
    }

    protected function listenToMemberChanges(): void
    {
        return;
        $this->on("node.added", function() {
            $this->persist();
        });
        $this->on("node.removed", function() {
            $this->persist();
        });
    }

}