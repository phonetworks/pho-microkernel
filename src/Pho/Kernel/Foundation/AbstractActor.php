<?php

namespace Pho\Kernel\Foundation;

use Pho\Framework;
use Pho\Kernel\Kernel;

abstract class AbstractActor extends Framework\Actor {

    use \Pho\Kernel\Bridge\NodeHydratorTrait;

    public function __construct(Kernel $kernel, Framework\GraphInterface $graph)
    { 
        $this->kernel = $kernel;
        $this->graph = $graph;
        $this->acl = Acl\AclFactory::seed($kernel, $this, static::DEFAULT_MODE);
        parent::__construct($graph);
        $this->persist($this->loadEditorsFrame());
    }

    public function acl(): Acl\AbstractAcl
    {
        return $this->acl;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        if(isset($this->acl))
            $array["acl"] = $this->acl->toArray();
        return $array;
    }

}