<?php

namespace Pho\Kernel\Traits\Node;

use Pho\Kernel\Kernel;
use Pho\Kernel\Acl;
use Pho\Framework;
use Pho\Lib\Graph\ID;
use Pho\Lib\Graph;
use Pho\Kernel\Standards;
use Pho\Kernel\Bridge;

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
        if(!static::T_PERSISTENT)
            return;
        $this->kernel->gs()->touch($this);
    }

    

    public function serialize(): string
    {
        if(!static::T_PERSISTENT) {
            return parent::serialize();
       }
        $this->kernel->logger()->info("About to serialize the node  %s, a %s", $this->id(), $this->label());
        $x = serialize($this->toArray());
        $this->kernel->logger()->info("The node serialized as: %s", $x);
        return $x;
    }

  public function unserialize(/* mixed */ $data): void
  {
      if(!static::T_PERSISTENT) {
            parent::unserialize($data);
            return;
       }
    $this->kernel = $GLOBALS["kernel"];
    $data = unserialize($data);
    $this->id = ID::fromString($data["id"]);
    $this->kernel->logger()->info("Unserialization begins for %s", $this->id());
    $this->attributes = new Graph\AttributeBag($this, $data["attributes"]);
    $this->kernel->logger()->info("The edge list is as follows: %s", print_r($data["edge_list"], true));
    $this->edge_list = new Graph\EdgeList($this, $data["edge_list"]);
    if((string) ID::root() == $data["context"]) {
        $this->context = new Standards\Space($this->kernel);
        $this->context_id = $data["context"];
    }
    else {
        $this->context_id = $data["context"];
    }
    
    $this->creator_id = $data["creator"];
    if(isset($data["current_context"])) { // Actor
        $this->current_context = $this->kernel->gs()->node($data["current_context"]);
    }
    if(isset($data["members"])) { // Frame
        $this->kernel->logger()->info(
            "Extracting members for the frame %s: %s",
            $this->id(),
            print_r($data["members"], true)
        );
        $this->loadNodesFromIDArray($data["members"]);
    }
    if(isset($data["acl"])) {
        $this->acl = Acl\AclFactory::seed($this->kernel, $this, $data["acl"]["permissions"]);
    }

    if(isset($data["editors"])) {
        $this->editors = $this->kernel->gs()->node($data["editors"]);
    }
    if(isset($data["notifications"]) && $this instanceof Framework\Actor) {
        $notifications = array();
        foreach($data["notifications"] as $notification) {
            // let's recreate the objects
            $class = $notification["class"];
            if(!class_exists($class) || !preg_match("/^[a-z0-9_\\\\]+$/i", $class)) {
                continue;
            }
            $edge_id = (string) ID::fromString($notification["edge"]);
            $notifications[] = eval("new class(\"".$edge_id."\") extends ".$class." {
                use Bridge\NotificationHydratorTrait;
            };");
        }
        $this->notifications = new Framework\NotificationList($this, $notifications); // assuming it's an actor
    }
    $this->setupEdges();
  }


     protected function _callSetter(string $name, array $args): \Pho\Lib\Graph\EntityInterface
     {
         if(!static::T_PERSISTENT)
            return parent::_callSetter($name, $args);

         $edge = parent::_callSetter($name, $args);
         $this->kernel->logger()->info("Saving edge %s", $edge->id());
         $this->kernel->gs()->touch($edge);
         $this->persist();
         if($edge->tail()->id()==$this->id()) {
            $edge->head()->persist();
         }
         else {
            $edge->tail()->persist();
         }
         return $edge->return();
     }


     protected function _callFormer(string $name, array $args): \Pho\Lib\Graph\EntityInterface
     {
         if(!static::T_PERSISTENT)
            return parent::_callFormer($name, $args);

            $class = $this->__findFormativeClass($name, $args);
        if(count($args)>0) {
            $head = new $class($this->kernel, $this, $this->where(), ...$args);
        }
        else {
            $head = new $class($this->kernel, $this, $this->where());
        }
        
        $edge_class = $this->edge_out_formative_edge_classes[$name];
        $edge = new $edge_class($this, $head);
        $this->kernel->gs()->touch($edge);
        $this->kernel->gs()->touch($head);
        $this->kernel->gs()->touch($edge->tail());
        return $edge->return();

     }

     public function registerEdgeOutClass(string $class, int $trim = 3): void
     {
        parent::registerEdgeOutClass($class, $trim);
     }
     

   public function destroy(): void
   {
       if(!static::T_PERSISTENT) {
           parent::destroy();
            return;
       }
    $edges_in = $this->edges()->in();
    $edges_out = $this->edges()->out();
    foreach($edges_in as $edge) {
        $edge->destroy();
    }
    foreach($edges_out as $edge) {
        if($edge->predicate()->binding()) {
            $this->kernel->logger()->info("Deleting edge head node %s with label: %s", $edge->head()->id(), $edge->head()->label());
            $edge->head()->destroy();
        }
        $edge->destroy();
    }
    $this->kernel->logger()->info("Node %s with label: %s has been called for deletion", $this->id(), $this->label());
   $this->kernel->database()->del(sprintf("node:%s", $this->id()));
   parent::destroy();
   }


}