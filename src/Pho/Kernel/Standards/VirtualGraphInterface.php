<?php

namespace Pho\Kernel\Standards;

use Pho\Lib\Graph\ID;

interface VirtualGraphInterface 
{
    /**
     * Stores the master's ID
     *
     * Used in construction. Not used in practice, but as a reference.
     * 
     * @param ID $id The ID of the master
     * 
     * @return VirtualGraphInterface The object itself.
     */
    public function withMaster(ID $id): VirtualGraphInterface;
}