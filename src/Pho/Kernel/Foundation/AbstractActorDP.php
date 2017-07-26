<?php

namespace Pho\Kernel\Foundation;

/**
 * An AbstractActor Decorator with Deferred Persistence
 * 
 * Does not perform persistence.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
abstract class AbstractActorDP extends AbstractActor {

    public function __construct(...$args)
    { 
        $this->deferred_persistence = true;
        parent::__construct(...$args);
    }
}