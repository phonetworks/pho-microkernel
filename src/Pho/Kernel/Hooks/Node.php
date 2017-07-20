<?php

namespace Pho\Kernel\Hooks;

use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\GraphInterface;
use Pho\Lib\Graph\EdgeInterface;
use Pho\Framework\Actor;

/**
 * Compat layer between the kernel and lower level packages
 * 
 * This trait is a compatibility layer between the kernel and 
 * lower level packages, namely pho-lib-graph and pho-framework.
 * Both of these packages provide hydration functions useful to
 * implement persistence at higher levels. This trait is 
 * responsible of implementing these hydration/persistence methods
 * for the kernel.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Node 
{
    public static function setup(NodeInterface $node): void
    {
        $node->hook("context", (function(): GraphInterface {
                $this->context = $this->kernel->gs()->node($this->context_id);
                return $this->context;
            })
        );

        $node->hook("creator", (function(): Actor {
                $this->creator = $this->kernel->gs()->node($this->creator_id);
                return $this->creator;
            })
        );

        $node->hook("edge", (function(): EdgeInterface {
                $this->kernel->logger()->info("Hydrating edge %s", $id);
                return $this->kernel->gs()->edge($id);
            })
        );
    }
}