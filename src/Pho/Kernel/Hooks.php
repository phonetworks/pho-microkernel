<?php

namespace Pho\Kernel;

use Pho\Lib\Graph;
use Pho\Framework\AbstractNotification;

/**
 * Hooks class.
 *
 * As its name implies, this class hooks objects (node, edge, graph or notification)
 * with theirs hydrator methods. To illustrate, a node object that has just been 
 * restored from the database would have its creator_id set up, but not its creator 
 * object. That is because, creator, as an object, is not stored in the database. 
 * Hooks hydrate this object, that is currently in its flat form, by fetching these 
 * associated objects based on their IDs.
 *
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Hooks
{
    /**
     * Detects the object type and calls the right Hooks class.
     *
     * @param mixed $obj The object.
     *
     * @return void
     */
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
