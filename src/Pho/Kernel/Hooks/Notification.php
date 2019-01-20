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
                $kernel = $GLOBALS["kernel"];
                return $kernel->gs()->edge($this->edge_id->toString());
            })
        );
    }
}
