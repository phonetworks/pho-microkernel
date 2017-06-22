<?php

namespace Pho\Kernel\Foundation;

use Pho\Kernel\Traits\PersistentGraphTrait;
use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Acl;

/**
 * do not extend group, 
 */
class VirtualGraph extends Framework\Graph {

    use PersistentGraphTrait;

    /**
     * The owner can do anything,
     * the others nothing.
     */
    const DEFAULT_MODE = 0x0f000;

    /**
     * no one can play with the acl of this group in any way
     */
    const DEFAULT_MASK = 0xfffff;

    public function __construct(Kernel $kernel, Framework\Actor $actor, Framework\ContextInterface $context)
    { 
        parent::__construct($actor, $context);
        $this->loadNodeTrait($kernel);
    }

}