<?php

namespace Pho\Kernel\Foundation;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Standards;
use Pho\Kernel\Acl;

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

    protected function particlize(Kernel $kernel, Framework\ContextInterface $graph): void
    {  
       $this->kernel = $kernel;
        $this->graph = $graph;
        $this->acl = Acl\AclFactory::seed($kernel, $this, static::DEFAULT_MOD); 
        $this->setEditability();
        $this->persist();
        $this->expire();
        // versionable trait -- work in progress.
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

}