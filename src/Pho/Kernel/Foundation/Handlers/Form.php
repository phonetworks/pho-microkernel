<?php

namespace Pho\Kernel\Foundation\Handlers;

use Pho\Framework\ParticleInterface;

/**
 * Kernel adapter of the Form Handler class.
 * 
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Form extends \Pho\Framework\Handlers\Form {

    /**
     * {@inheritDoc}
     */
        /**
     * Forms the head particle.
     *
     * @param ParticleInterface $particle The particle that this handler is working on.
     * @param array  $pack Holds cargo variables extracted by loaders.
     * @param string $name Catch-all method name
     * @param array  $args Catch-all method arguments
     * 
     * @return \Pho\Lib\Graph\NodeInterface
     */
    protected static function formHead(
        ParticleInterface $particle,
        array $pack,
        string $name, 
        array $args): \Pho\Lib\Graph\NodeInterface
    {
        $class = static::findFormativeClass($name, $args, $pack);
        if(count($args)>0) {
            return new $class($particle->kernel(), $particle, $particle->where(), ...$args);
        }
        return new $class($particle->kernel(), $particle, $particle->where());
    }

}