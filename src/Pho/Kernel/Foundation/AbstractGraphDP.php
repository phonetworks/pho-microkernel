<?php

namespace Pho\Kernel\Foundation;

/**
 * An AbstractGraph Decorator with Deferred Persistence
 * 
 * Does not perform persistence.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
abstract class AbstractGraphDP extends AbstractGraph {

    public function __construct(...$args)
    { 
        $this->deferred_persistence = true;
        parent::__construct(...$args);
    }
}