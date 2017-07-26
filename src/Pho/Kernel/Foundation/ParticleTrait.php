<?php

namespace Pho\Kernel\Foundation;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Standards;
use Pho\Kernel\Acl;
use Pho\Kernel\Hooks;

trait ParticleTrait
{

    use \Pho\Kernel\Traits\Node\EditableTrait;
    use \Pho\Kernel\Traits\Node\PersistentTrait;
    use \Pho\Kernel\Traits\Node\EphemeralTrait;
    //use \Pho\Kernel\Traits\Node\VersionableTrait;

    protected $kernel;
    protected $graph;
    protected $acl;
    protected $editors;
    protected $deferred_persistence = false;

    protected function hydrate(
        Kernel $kernel, 
        Framework\ContextInterface $graph): void
    {  
        $this->kernel = $kernel;
        $this->graph = $graph;
        $this->acl = Acl\AclFactory::seed($kernel, $this, static::DEFAULT_MOD); 
        
        $this->setEditability();
        if( !$this->deferred_persistence && !$this instanceof Standards\VirtualGraphInterface ) // not at construction.
            $this->persist();
        else $this->kernel->logger()->info("Working on a VirtualGraphInterface object");
        $this->expire();
        $this->rewire();
        // versionable trait -- work in progress.
    }

    public function rewire(): self
    {
        Hooks::setup($this);

        $this->on("modified", function() {
            $this->persist();
        });
        $this->on("edge.created", function($edge) {
            $this->persist();
            $edge->inject("kernel", $this->kernel);
            $edge->rewire()->persist();
            if(!$edge->orphan())
                $edge->head()->persist();
        });
        $this->on("edge.connected", function($edge) { // this must be the head.
            $this->persist();
            $edge->persist();
        });

        return $this;
    }

    public function acl(): Acl\AbstractAcl
    {
        return $this->acl;
    }

    // overriding
    public function toArray(): array
    {
        $array = parent::toArray();
        if(isset($this->acl))
            $array["acl"] = $this->acl->toArray();
        if(static::T_EDITABLE)
            $array["editors"] = (string) $this->editors->id();
        return $array;
    }

    // overriding  pho-lib-graph entitytrait
    // does nothing
    public function observeAttributeBagUpdate(\SplSubject $subject): void
    {
        $this->persist();
    }

    //  overriding pho-lib-graph node.php
    // adds persistence
    public function changeContext(\Pho\Lib\Graph\GraphInterface $context): void
    {
        parent::changeContext($context);
        $this->persist();
    }

    public function kernel(): Kernel
    {
        if(!isset($this->kernel))
            $this->kernel = $GLOBALS["kernel"];
        return $this->kernel;
    }

}