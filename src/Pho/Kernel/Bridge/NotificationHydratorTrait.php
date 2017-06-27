<?php

namespace Pho\Kernel\Bridge;

use Pho\Lib\Graph\EdgeInterface;

trait NotificationHydratorTrait {

    private $kernel;

    /**
     * Constructor.
     *
     * In this level, notification has a different type of constructor
     * that requires the ID only, instead of the edge object itself.
     * Requiring the edge object would consume too much resources by
     * waking up way too many objects unnecessarily. Therefore, we 
     * prefer lazy-loading.
     * 
     * @param string $edge_id
     */
    public function __construct(string $edge_id) {
        $this->edge_id = $edge_id;
    }

    private function _ensureKernel(): void
    {
        if(!isset($this->kernel))
            $this->kernel = $GLOBALS["kernel"];
    }

    protected function hydratedEdge(): EdgeInterface
    {
        $this->_ensureKernel();
        return $this->kernel->ns()->edge($this->edge_id);
    }

}