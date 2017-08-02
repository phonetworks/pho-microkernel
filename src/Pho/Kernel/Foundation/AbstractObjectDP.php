<?php

namespace Pho\Kernel\Foundation;

/**
 * An AbstractObject Decorator with Deferred Persistence
 * 
 * Does not perform persistence.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
abstract class AbstractObjectDP extends AbstractObject {

    public function __construct(...$args)
    { 
        $this->deferred_persistence = true;
        parent::__construct(...$args);
    }
}