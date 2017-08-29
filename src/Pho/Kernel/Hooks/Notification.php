<?php

namespace Pho\Kernel\Hooks;

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
        $notification->hook("edge", (function(string $edge_id) {
                $this->_ensureKernel();
                return $this->kernel->ns()->edge($this->edge_id);
            })
        );
    }
}
