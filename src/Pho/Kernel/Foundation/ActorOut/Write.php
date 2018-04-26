<?php

namespace Pho\Kernel\Foundation\ActorOut;

use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;
use Pho\Kernel\Foundation\Exceptions\WritePermissionException;

class Write extends \Pho\Framework\ActorOut\Write
{
    //use \Pho\Kernel\Traits\Edge\PersistentTrait;

    public function __construct(NodeInterface $tail, ?NodeInterface $head = null, ?PredicateInterface $predicate = null, ...$args) 
    {
        if(method_exists($tail->where(), "acl") && !$tail->where()->acl()->writeable($tail)) {
            throw new WritePermissionException($tail, $tail->where());
        }
        parent::__construct($tail, $head, $predicate, ...$args);
        $this->kernel = $GLOBALS["kernel"];
        $this->kernel["gs"]->cache($this); // we want this early because cache will be called.
    }
}