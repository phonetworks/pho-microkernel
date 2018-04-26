<?php

namespace Pho\Kernel\Foundation\ObjectOut;

use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;

class Mention extends \Pho\Framework\ObjectOut\Mention
{
    //use \Pho\Kernel\Traits\Edge\PersistentTrait;
    
    public function __construct(NodeInterface $tail, ?NodeInterface $head = null, ?PredicateInterface $predicate = null, ...$args) 
    {
        parent::__construct($tail, $head, $predicate, ...$args);
        $this->kernel = $GLOBALS["kernel"];
        $this->kernel->gs()->cache($this); // we want this early because cache will be called.
    }
}