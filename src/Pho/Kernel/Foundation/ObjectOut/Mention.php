<?php

namespace Pho\Kernel\Foundation\ObjectOut;

use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;

class Mention extends \Pho\Framework\ObjectOut\Mention
{
    //use \Pho\Kernel\Traits\Edge\PersistentTrait;
    
    public function __construct(NodeInterface $tail, ?NodeInterface $head = null, ?PredicateInterface $predicate = null) 
    {
        parent::__construct($tail, $head, $predicate);
        $this->kernel = $GLOBALS["kernel"];
    }
}