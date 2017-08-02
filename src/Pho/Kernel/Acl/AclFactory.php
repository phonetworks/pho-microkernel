<?php

namespace Pho\Kernel\Acl;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Foundation;

class AclFactory {
    
    public static function seed(Kernel $kernel, Framework\ParticleInterface $particle, /*int|array*/ $permissions = 0x0e754): AclInterface 
    {
        if($particle instanceof Foundation\AbstractActor) {
            return new ActorAcl($kernel, $particle, $permissions);
        }
        elseif($particle instanceof Foundation\AbstractGraph) {
            return new GraphAcl($kernel, $particle, $permissions);
        }
        elseif($particle instanceof Foundation\AbstractObject) {
            return new ObjectAcl($kernel, $particle, $permissions);
        }
        throw new IncompatibleParticleException($particle->acl());
    }

}