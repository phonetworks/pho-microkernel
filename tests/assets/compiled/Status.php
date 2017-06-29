<?php

namespace PhoNetworksAutogenerated;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Traits;
use Pho\Kernel\Foundation;




/*****************************************************
 * This file was auto-generated by pho-compiler
 * For more information, visit http://phonetworks.org
 ******************************************************/

class Status extends Foundation\AbstractObject {

    const T_EDITABLE = false;
    const T_PERSISTENT = true;
    const T_EXPIRATION =  0;
    const T_VERSIONABLE = false;
    
    const DEFAULT_MOD = 0x0e444;
    const DEFAULT_MASK = 0xeeeee;

    protected function onIncomingEdgeRegistration(): void
    {
        $this->registerIncomingEdges(UserOut\Post::class);
        $this->registerIncomingEdges(UserOut\Like::class);
    }

}

/*****************************************************
 * Timestamp: 1498724736
 * Size (in bytes): 1009
 * Compilation Time: 7290
 * 3bf0ecdfbcb0d0d0c7e76daf17570e84
 ******************************************************/