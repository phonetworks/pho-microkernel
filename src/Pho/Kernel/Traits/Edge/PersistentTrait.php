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

    /**
     * {@inheritDoc}
     */
   public function unserialize(/* mixed */ $data): void
    {
        parent::unserialize($data);
        $this->inject("kernel", $GLOBALS["kernel"]);
        $this->rewire();
        $this->init(); // for signals
    }


        public function rewire(): self
    {
        Hooks::setup($this);
        
        $this->on("modified", function() {
            $this->persist();
        });

        $this->on("deleting", function() { // this must be the head.
            $this->tail()->edges()->delete($this->id());
            if(!$this->orphan())
                $this->head()->edges()->delete($this->id());
            $this->injection("kernel")->gs()->delEdge($this->id());
            //self::__destruct();
        });

        return $this;
    }

}