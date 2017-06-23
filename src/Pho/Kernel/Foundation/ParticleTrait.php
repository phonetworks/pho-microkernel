<?php

trait ParticleTrait
{
    protected $kernel, $graph, $acl, $editors;

    protected function particlize(Kernel $kernel, Framework\GraphInterface $graph): void
    {
        
    }


    // EDITABILITY

    protected function makeEditable(): void
    {
        if(!static::T_EDITABLE)
            return;
        $this->editors = new Standards\VirtualGroup($this->kernel, $this->creator(), $this->context());
        $this->acl()->set("g:".(string) $this->editors->id().":", 
            $this->acl()->sticky() ? $this->acl()->get("a::") : $this->acl()->get("u::")
        );
    }

    // not hydrated, can be .
    public function editors(): ?Foundation\VirtualGroup
    {
        return $this->editors;
    }

    // PERSISTENCE
    public function persist(): void
    {
        if(!static::T_PERSISTENT) 
            return;
        $this->kernel->gs()->touch($this);
    }

}