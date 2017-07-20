<?php

namespace Pho\Kernel\Hooks;

class Notification
{
    public static function setup(AbstractNotification $notification): void
    {
        $notification->hook("edge", function(string $edge_id) {
            $this->_ensureKernel();
            return $this->kernel->ns()->edge($this->edge_id);
        });
    }
}