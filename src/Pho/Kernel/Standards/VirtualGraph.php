<?php

namespace Pho\Kernel\Standards;

use Pho\Kernel\Foundation;
use Pho\Kernel\Kernel;

/**
 * do not extend group, 
 */
class VirtualGraph extends Foundation\AbstractGraph {

    /**
     * The owner can do anything,
     * the others nothing.
     */
    const DEFAULT_MODE = 0x0f000;

    /**
     * no one can play with the acl of this group in any way
     */
    const DEFAULT_MASK = 0xfffff;

    const T_EDITABLE = false; // not recursive please!
    const T_PERSISTENT = true;
    const T_EXPIRATION = 0;
    const T_VERSIONABLE = false;

}