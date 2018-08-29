<?php

namespace Pho\Kernel\Foundation\Exceptions;

use Pho\Framework\ParticleInterface;
use Pho\Lib\Graph\EntityInterface;

class WriteForEdgePermissionException extends \Exception
{
    public function __construct(EntityInterface $edge, ParticleInterface $node)
    {
        parent::__construct();
        $this->message = sprintf(
            "The edge %s (a %s) cannot be edited by the node %s (a %s)",
            (string) $edge->id(),
            get_class($edge),
            (string) $node->id(),
            get_class($node)
        );
    }
}