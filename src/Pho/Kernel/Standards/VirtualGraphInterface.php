<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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