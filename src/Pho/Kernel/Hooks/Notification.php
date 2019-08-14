<?php

namespace Pho\Kernel\Hooks;

use Pho\Lib\Graph\EdgeInterface;
use Pho\Framework\AbstractNotification;

/**
 * {@inheritDoc}
 */
class Notification
{
    /**
     * {@inheritDoc}
     */
    public static function setup(AbstractNotification $notification): void
    {
        $notification->hook("edge", (function(): EdgeInterface 
            {
                if(!isset($this->kernel))
                    $this->kernel = $GLOBALS["kernel"];
                $this->kernel->logger()->info("Edge id is: ".$$this->edge_id);
                return $this->kernel->gs()->edge($this->edge_id);
            })
        );
    }
}
