<?php

namespace Pho\Kernel\Traits\Node;

/**
 * Editable Trait
 * 
 * Nodes that use the "Editable Trait" can be modified
 * not only by its author or creator, but also by 
 * a group of actors, that the author may assign or 
 * delegate this task to.
 * 
 * Editable trait works by creating a virtual subgraph of editor
 * actors at its implementor's initialization.
 * 
 * Only persistent nodes can make use of the editable trait. This 
 * trait has not been tested with volatile nodes.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
trait EditableTrait {

    use PersistentTrait {
        PersistentTrait::toArray as _toArray;
    }

    /**
     * The list of editors
     *
     * @var VirtualGroup
     */
    protected $editors;

    public function loadEditorsFrame(): bool
    {
        $this->editors = new Standards\VirtualGroup($this->kernel, $this->creator(), $this->context());
        //if($this->acl()->sticky()) echo "x";
        //$this->acl()->sticky() ? $this->acl()->get("a::") : $this->acl()->get("u::");
        $this->acl()->set("g:".(string) $this->editors->id().":", 
            $this->acl()->sticky() ? $this->acl()->get("a::") : $this->acl()->get("u::")
        );
        return false;
    }

    // not hydrated, can be .
    public function editors(): Foundation\VirtualGroup
    {
        return $this->editors;
    }

    public function toArray(): array
    {
        $array = $this->_toArray();
        $array["editors"] = $this->editors->id();
        return $array;
    }

}