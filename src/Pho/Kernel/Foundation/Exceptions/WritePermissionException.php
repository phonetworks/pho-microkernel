<?php

namespace Pho\Kernel\Foundation\Exceptions;

use Pho\Framework\ParticleInterface;

class WritePermissionException extends \Exception
{
    public function __construct(ParticleInterface $node, ParticleInterface $where)
    {
        parent::__construct();
        $this->message = sprintf(
            "The node %s (a %s) cannot write in %s (a %s with permissions as %s)",
            (string) $node->id(),
            (string) $node->label(),
            (string) $where->id(),
            (string) $where->label(),
            (string) print_r($where->acl()->toArray(), true)
        );
    }
}