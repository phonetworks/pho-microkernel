<?php

namespace Pho\Kernel\Hooks;

use Pho\Lib\Graph\EdgeInterface;

/**
 * {@inheritDoc}
 */
class Notification
{
    /**
     * {@inheritDoc}
     */
    public static function setup(\Pho\Framework\AbstractNotification $notification): void
    {
        $notification->hook("edge", (function(): EdgeInterface 
            {
                //$this->kernel = $GLOBALS["kernel"];
                return $this->owner->kernel()->gs()->edge($this->edge_id->toString());
            })
        );
    }
}
