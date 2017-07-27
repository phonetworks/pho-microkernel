<?php

namespace Pho\Kernel\Foundation;

use Pho\Kernel\Acl\AbstractAcl;

interface ParticleInterface
{
    
    public function acl(): AbstractAcl;
}