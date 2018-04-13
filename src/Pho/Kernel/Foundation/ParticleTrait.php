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
    protected $rewired = false;

    protected function registerSetHandler(): void
    {
        $this->registerHandler(
            "set",
            \Pho\Kernel\Foundation\Handlers\Set::class
        );
    }

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
        else {
            $this->kernel->logger()->info("Persistence skipped with %s. Deferred persistence: %s",
                get_class($this),
                (string) (int) $this->deferred_persistence
            );
        }
        $this->expire();
        $this->rewire();
        // versionable trait -- work in progress.
    }

    /**
     * Undocumented function
     *
     * Can be forced to work multiple times, otherwise there's a lock.
     * 
     * @return self
     */
    public function rewire($force=false): self
    {
        if(!$force && $this->rewired)
            return $this;

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

        $this->on("deleting", function() { // this must be the head.
            if($this->persistable())
                $this->kernel->gs()->delNode($this->id());
        });

        $this->rewired = true;
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

        if(isset($this->memberships))
            $array["memberships"] = $this->memberships;

        if(static::T_EDITABLE)
            $array["editors"] = (string) $this->editors->id();
        
        // deflating attributes
        $tpl = implode("%s", Kernel::PARTICLE_IN_ATTRIBUTEBAG_TPL);
        foreach($array["attributes"] as $key=>$value) {
            if($value instanceof ParticleInterface) {
                $this->kernel->logger()->info("Deflating the attribute: %s", $key);
                $array["attributes"][$key] = sprintf($tpl, (string) $value->id());
            }
        }

        return $array;
    }

    // overriding  pho-lib-graph entitytrait
    // does nothing
    public function observeAttributeBagUpdate(\SplSubject $subject): void
    {
        $this->persist();
    }
    
    public function kernel(): Kernel
    {
        if(!isset($this->kernel))
            $this->kernel = $GLOBALS["kernel"];
        return $this->kernel;
    }

}