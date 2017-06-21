<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel;

/**
 * Kernel base.
 * 
 * A base class for kernel helper methods, not exposed 
 * directly to end users.
 * 
 * Extends Symfony \Pimple for container interoperability.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Container extends \Pimple
{
   /**
    * @internal
    *
    * This magic method is used as a shortcut to kernel services. It works
    * in conjunction with Pimple container. 
    *
    * @param string $name Method name.
    * @param string $arguments Method arguments.
    * @return mixed
    */
   public function __call($name, $arguments) {
        if( $this->is_configured && 
        ( in_array($name, ["config", "utils", "graph", "world"]) || // preset ones.
          in_array($name, array_keys($this["config"]->services->toArray())))
        
        ) {
            return $this[$name];
        }
   }
}