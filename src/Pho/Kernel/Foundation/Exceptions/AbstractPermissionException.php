<?php

namespace Pho\Kernel\Foundation\Exceptions;

use Pho\Framework\ParticleInterface;

abstract class AbstractPermissionException extends \Exception
{
    public function __construct(ParticleInterface $head, ParticleInterface $tail, string $verb)
    {
        parent::__construct();
        $this->message = sprintf(
            "The node %s cannot be %s by the node %s",
            (string) $head->id(),
            $verb,
            (string) $tail->id()
        );
    }
}