<?php

namespace Pho\Kernel\Foundation;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Standards;

abstract class AbstractActor extends Framework\Actor {

    use \Pho\Kernel\Bridge\NodeHydratorTrait;
    use ParticleTrait;

    /**
     * The number of arguments to cut off.
     * 
     * With formative methods, there are a number of constructor arguments
     * that needs to be skipped, in order to find the arguments that 
     * actually change how the particle is set up.
     * 
     * While with framework, this number is set to be 2, with
     * kernel it's 3, because in addition, the $kernel is passed to all
     * new particles.
     * 
     * This constant needs to be defined with Actor only.
     * 
     */
    const FORMATIVE_TRIM_CUT = 3;

    public function __construct(Kernel $kernel, Framework\ContextInterface $graph)
    { 
        parent::__construct($graph);
        $this->registerHandlerAdapter(
            "form",
            \Pho\Kernel\Foundation\Handlers\Form::class);
        $this->particlize($kernel, $graph);
        
    }
}