<?php

namespace Pho\Kernel\Traits\Edge;

use Pho\Kernel\Kernel;
use Pho\Kernel\Hooks;

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


        public function rewire(): self
    {
        Hooks::setup($this);
        
        return $this;
    }

}