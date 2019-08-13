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
        //$this->init(); // for signals
    }


        public function rewire(): self
    {
        Hooks::setup($this);
        $notifications = $this->notifications();
        foreach($notifications as $notification) {
            Hooks::setup($notification);
        }

        $this->on("modified", [$this, "persist"]);

        $this->on("deleting", [$this, "onDeleting"]);

        return $this;
    }

    function listeners(string $eventName, bool $flat=false) : array {
        $res = parent::listeners($eventName, $flat);
        if($flat)
          return $res;
        foreach($res as $k=>$r) {
          if(!\is_callable($r)) {
              $res[$k][0] = 
                  ID::root()->toString() === $r[0] ? 
                      $this->kernel->space() : 
                      $this->id()->toString() === $r[0] ? $this : $this->kernel->gs()->node($r[0])
              ;
          }
        }
        return $res;
    }

    public function onDeleting() { 
            $this->tail()->edges()->delete($this->id());
            if(!$this->orphan())
                $this->head()->edges()->delete($this->id());
            $this->injection("kernel")->gs()->delEdge($this->id());
            //self::__destruct();
    }

}