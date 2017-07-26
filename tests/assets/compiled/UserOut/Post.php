<?php

namespace PhoNetworksAutogenerated\UserOut 
{

use Pho\Kernel\Traits\Edge\PersistentTrait;
use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;
use Pho\Framework;



/*****************************************************
 * This file was auto-generated by pho-compiler
 * For more information, visit http://phonetworks.org
 ******************************************************/

class Post extends Framework\ActorOut\Write {

    
    use PersistentTrait;
    

    const HEAD_LABEL = "post";
    const HEAD_LABELS = "posts";
    const TAIL_LABEL = "author";
    const TAIL_LABELS = "authors";
    

    const FORMABLES = [\PhoNetworksAutogenerated\Status::class];
    

    public function __construct(NodeInterface $tail, NodeInterface $head, ?PredicateInterface $predicate = null) 
    {
        parent::__construct($tail, $head, $predicate);
        $this->kernel = $GLOBALS["kernel"];
    }

}

/* Predicate to load as a partial */
class PostPredicate extends Framework\ActorOut\WritePredicate
{
    protected $binding = true;
    
    const T_CONSUMER = true;
    const T_NOTIFIER = false;
    const T_SUBSCRIBER = false;
    const T_FORMATIVE = true;
    const T_PERSISTENT = true;
}
/* Notification to load if it's a subtype of write or mention. */
class PostNotification extends Framework\ActorOut\WriteNotification
{

}
}

/*****************************************************
 * Timestamp: 
 * Size (in bytes): 1567
 * Compilation Time: 28332
 * 01bd6a2e4bf215e7cf6aae422237db92
 ******************************************************/