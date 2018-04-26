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
        ( in_array($name, ["config", "gs", "graph", "space", "founder"]) || // preset ones.
          in_array($name, array_keys($this["config"]->services->toArray())))
        
        ) {
            if(!isset($this->values[$name])) {
              if($name=='logger') {
                echo "Logger is not available yet".PHP_EOL;
                return new class() { public function __call(string $name, array $arguments) {
                    // printf($arguments[0], ...array_shift($arguments));
                  } 
                };
              }
              throw new \InvalidArgumentException(sprintf("The method %s is not defined", $name));
            }
            return $this[$name];
        }
   }
}