<?php

namespace Pho\Kernel\Standards;

use Pho\Kernel\Foundation;
use Pho\Kernel\Kernel;
use Pho\Lib\Graph\ID;

/**
 * do not extend group, 
 */
class VirtualGraph extends Foundation\AbstractGraphDP implements VirtualGraphInterface {

    /**
     * The owner can do anything,
     * the others nothing.
     */
    const DEFAULT_MOD = 0x0f000;

    /**
     * no one can play with the acl of this group in any way
     */
    const DEFAULT_MASK = 0xfffff;

    const T_EDITABLE = false; // not recursive please!
    const T_PERSISTENT = true;
    const T_EXPIRATION = 0;
    const T_VERSIONABLE = false;

    protected $master;

    public function toArray(): array
    {
        $array = parent::toArray();
        $array["master"] = (string) $this->master;
        return $array;
    }

    /**
     * {@inheritDoc}
     */
    public function withMaster(ID $id): VirtualGraphInterface
    {
        $this->master = (string) $id;
        $this->persist();
        return $this;
    }

}