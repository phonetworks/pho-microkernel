<?php
namespace Pho\Kernel\Bridge;

use Pho\Lib\Graph;

trait EdgeHydratorTrait {
    
    private $kernel;

    private function persist(): void
    {
        $this->ensureKernel();
        $this->kernel->database()->set(sprintf("edge:%s", $this->id()), serialize($this));
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
        $this->head = $this->kernel->utils()->node($this->head_id);
        return $this->head;
    }
    
    public function hydratedTail(): Graph\NodeInterface
    {
        $this->_ensureKernel();
        $this->tail =  $this->kernel->utils()->node($this->tail_id);
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
        $this->kernel->database()->del(sprintf("edge:%s", $this->id()));
   }
}