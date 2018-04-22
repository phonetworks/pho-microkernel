<?php

namespace Pho\Kernel\Foundation;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Standards;

abstract class AbstractActor extends Framework\Actor implements ParticleInterface {

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

    /**
     * Lists the subgroup memberships of the Actor
     * 
     * An array of IDs in string format.
     *
     * @var array
     */
    protected $memberships = [];

    public function __construct(Kernel $kernel, Framework\ContextInterface $graph)
    { 
        parent::__construct($graph);
        $this->registerSetHandler();
        $this->registerHandler(
            "form",
            \Pho\Kernel\Foundation\Handlers\Form::class
        );
        $this->hydrate($kernel, $graph);
        if($kernel->live()) {
            $this->kernel()->space()->emit("particle.added", [$this]);
        }
    }

    public function edit(ParticleInterface $obj): ParticleInterface
    {
        if(!$obj->acl()->writeable($this))
            throw new Exceptions\WriteByPermissionException($obj, $this);
        // $obj->lock($this);
        return $obj;
    }

    public function join(AbstractGraph $graph): void
    {
        if(!$graph->acl()->executable($this))
            throw new Exceptions\ExecutePermissionException($graph, $this);
        $graph->add($this);
        $this->memberships[] = (string) $graph->id();
        $this->persist();
    }
    
    public function leave(AbstractGraph $graph): void
    {
        if(!$graph->contains($this->id()))
            throw new \Exception(sprintf("No member with id %s", $this->id()->toString()));
        $graph->remove($this->id());
        unset($this->memberships[\array_search((string) $graph->id(), $this->memberships)]);
        $this->persist();
    }

    public function manage(ParticleInterface $obj): ParticleInterface
    {
        if(!$obj->acl()->manageable($this))
            throw new Exceptions\ManagePermissionException($obj, $this);
        // $obj->lock($this);
        return $obj;
    }
}
