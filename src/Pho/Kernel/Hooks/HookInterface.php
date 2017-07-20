<?php

namespace Pho\Kernel\Hooks;

/**
 * Hooks the given object with hydrators.
 * 
 * This helps the framework object become stateful and persist in the database.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
interface HookInterface
{
    /**
     * Sets up the hooks for given object.
     * 
     * @param mixed $obj It's either a \Pho\Lib\Graph, or \Pho\Lib\Graph\EntityInterface, or a \Pho\Framework\AbstractNotification
     * 
     * @return void
     */
    public static function setup(/*mixed*/ $obj): void;

}