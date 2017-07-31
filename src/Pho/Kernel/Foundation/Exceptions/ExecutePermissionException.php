<?php

namespace Pho\Kernel\Foundation\Exceptions;

use Pho\Framework\ParticleInterface;

class ExecutePermissionException extends AbstractPermissionException
{
    public function __construct(ParticleInterface $head, ParticleInterface $tail)
    {
        parent::__construct($head, $tail, "executed");
    }
}