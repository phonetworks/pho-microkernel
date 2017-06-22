<?php
namespace Pho\Kernel\Bridge;

use Pho\Lib\Graph;

trait EdgeHydratorTrait {
    
    private $kernel;

    private function persist(): void
    {
        $this->ensureKernel();
        $this->kernel->gs()->touch($this);
        $this->persist();
    }

    private function _ensureKernel(): void
    {
        if(!isset($this->kernel))
            $this->kernel = $GLOBALS["kernel"];
    }

    public function hydratedHead(): Graph\NodeInterface 
    {
        $this->_ensureKernel();
        $this->head = $this->kernel->gs()->node($this->head_id);
        return $this->head;
    }
    
    public function hydratedTail(): Graph\NodeInterface
    {
        $this->_ensureKernel();
        $this->tail =  $this->kernel->gs()->node($this->tail_id);
        return $this->tail;
    }
    
    public function hydratedPredicate(): Graph\PredicateInterface
    {
        $this->predicate = (new $this->predicate);
        return $this->predicate;
    }

    public function destroy(): void
   {
        $this->_ensureKernel();
        $this->kernel->gs()->delEdge($this->id());
   }
}