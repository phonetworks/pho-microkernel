<?php

namespace Pho\Kernel\Traits\Edge;

use Pho\Kernel\Kernel;

/**
 * Persistent Trait
 * 
 * Persistent nodes are not volatile, which means, they are stored
 * in the database, and they don't go away when the kernel 
 * is halted for any reason --voluntary or by accident--.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
trait PersistentTrait {

    public function persist(): void
    {
        $this->injection("kernel")->gs()->touch($this);
    }

    public function setupEdgeHooks(): void
    {
        $this->hook("add", function(Graph\NodeInterface $node): void {
            $this->persist();
        });
        $this->hook("remove", function(Graph\NodeInterface $node): void {
            $this->persist();
        });
        $this->hook("get", function(ID $node_id): Graph\NodeInterface {
            return $this->injection("kernel")->gs()->node($node_id);
        });
        $this->hook("members", function(): array {
            foreach($this->node_ids as $node_id) {
                $this->nodes[$node_id] = 
                    $this->injection("kernel")->gs()->node($node_id);
            }
            return $this->nodes;
        });
    }

    public function destroy(): void
   {
        $this->injection("kernel")->gs()->delEdge($this->id());
        parent::destroy();
   }

    /**
     * {@inheritDoc}
     */
   public function unserialize(/* mixed */ $data): void
    {
        parent::unserialize($data);
        $this->inject("kernel", $GLOBALS["kernel"]);
    }

}