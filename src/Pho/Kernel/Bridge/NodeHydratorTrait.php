<?php

namespace Pho\Kernel\Bridge;

use Pho\Lib\Graph;
use Pho\Lib\Graph\ID;
use Pho\Framework;

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
trait NodeHydratorTrait {

    // node
    protected function hydratedContext(): Graph\GraphInterface
    {
        $this->context = $this->kernel->utils()->node($this->context_id);
        return $this->context;
    }

    // particletrait
    protected function hydratedCreator(): Framework\Actor
    {
        $this->creator = $this->kernel->utils()->node($this->creator_id);
        return $this->creator;
    }

    public function hydratedEdge(string $id): Graph\EdgeInterface
   {
       $this->kernel->logger()->info("Hydrating edge %s", $id);
        return $this->kernel->utils()->edge($id);
   }

}