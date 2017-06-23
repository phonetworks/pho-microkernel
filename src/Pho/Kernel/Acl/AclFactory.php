<?php

namespace Pho\Kernel\Acl;

use Pho\Framework;
use Pho\Kernel\Kernel;

class AclFactory {
    
    public static function seed(Kernel $kernel, Framework\ParticleInterface $particle, /*int|array*/ $permissions = 0x0e754): AclInterface 
    {
        if($particle instanceof Framework\Actor) {
            return new ActorAcl($kernel, $particle, $permissions);
        }
        else if($particle instanceof Framework\Graph) {
            return new GraphAcl($kernel, $particle, $permissions);
        }
        else if($particle instanceof Framework\Object) {
            return new ObjectAcl($kernel, $particle, $permissions);
        }
        throw new IncompatibleParticleException($particle->acl());
    }

}