<?php
namespace Pho\Kernel\Bridge;

use Pho\Lib\Graph;

trait EdgeHydratorTrait {
    
    private $kernel;

    public function persist(): void
    {
        $this->_ensureKernel();
        $this->kernel->gs()->touch($this);
    }

    private function _ensureKernel(): void
    {
        if(!isset($this->kernel))
            $this->kernel = $GLOBALS["kernel"];
    }

    protected function setupEdgeHooks(): void
    {
        $this->hook("add", function(Graph\NodeInterface $node): void {
            $this->persist();
        });
        $this->hook("remove", function(Graph\NodeInterface $node): void {
            $this->persist();
        });
        $this->hook("get", function(ID $node_id): Graph\NodeInterface {
            return $this->kernel->utils()->node($node_id);
        });
        $this->hook("members", function(): array {
            foreach($this->node_ids as $node_id) {
                $this->nodes[$node_id] = $this->kernel->gs()->node($node_id);
            }
            return $this->nodes;
        });
    }

    public function hyHead(): Graph\NodeInterface 
    {
        $this->_ensureKernel();
        $this->head = $this->kernel->gs()->node($this->head_id);
        return $this->head;
    }
    
    public function hyTail(): Graph\NodeInterface
    {
        $this->_ensureKernel();
        $this->tail =  $this->kernel->gs()->node($this->tail_id);
        return $this->tail;
    }
    
    public function hyPredicate(): Graph\PredicateInterface
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