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

    protected $kernel;

    public function kernel(): Kernel
    {
        return $this->kernel;
    }

    public function persist(): void
    {
        $this->kernel()->gs()->touch($this);
    }

   public function destroy(): void
   {
       $this->kernel->gs()->delEdge($this->id());
   }


}