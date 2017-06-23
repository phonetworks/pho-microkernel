<?php

namespace Pho\Kernel\Foundation;

use Pho\Framework;
use Pho\Kernel\Kernel;

abstract class Actor extends Framework\Actor {

    use \Pho\Kernel\Bridge\NodeHydratorTrait;

    protected $kernel, $graph, $acl;

    public function __construct(Kernel $kernel, Framework\GraphInterface $graph)
    { 
        $this->kernel = $kernel;
        $this->graph = $graph;
        $this->acl = Acl\AclFactory::seed($kernel, $this, static::DEFAULT_MODE);
        parent::__construct($graph);
        $this->persist($this->loadEditorsFrame());
    }

}