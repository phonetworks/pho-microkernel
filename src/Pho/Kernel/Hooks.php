<?php

namespace Pho\Kernel;

use Pho\Lib\Graph;
use Pho\Framework\AbstractNotification;

class Hooks
{
    public static function setup(/* mixed */ $obj): void
    {
        if($obj instanceof Graph\GraphInterface) {
            Hooks\Graph::setup($obj);
        }
        // . (on purpose)
        if($obj instanceof Graph\EdgeInterface) {
            Hooks\Edge::setup($obj);
        }
        elseif($obj instanceof Graph\NodeInterface) {
            Hooks\Node::setup($obj);
        }
        elseif($obj instanceof AbstractNotification) {
            Hooks\Notification::setup($obj);
        }
    }
}