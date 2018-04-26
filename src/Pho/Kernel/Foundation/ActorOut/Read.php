<?php

namespace Pho\Kernel\Foundation\ActorOut;

use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;
use Pho\Kernel\Foundation\Exceptions\ReadPermissionException;

class Read extends \Pho\Framework\ActorOut\Read
{
    //use \Pho\Kernel\Traits\Edge\PersistentTrait;

    public function __construct(NodeInterface $tail, ?NodeInterface $head = null, ?PredicateInterface $predicate = null, ...$args) 
    {
        if(!is_null($head)&&!$head->acl()->readable($tail)) {
            throw new ReadPermissionException($head, $tail);
        }
        parent::__construct($tail, $head, $predicate, ...$args);
        
        $this->kernel = $GLOBALS["kernel"];
        $this->kernel["gs"]->cache($this); // we want this early because cache will be called.
    }
}