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

use Pho\Lib\Graph;
use Pho\Framework;
use Pho\Kernel\Services\Exceptions\AdapterNonExistentException;

/**
 * Pho Kernel is a programmable social graph interface.
 *
 * Pho Kernel is static, therefore you cannot run multiple kernels in a single
 * PHP thread. Yo may however halt the kernel (via halt() method) and relaunch.
 *
 * Example usage:
 *  ```php
 *  $kernel = new Pho\Kernel\Kernel();
 *  $kernel->boot();
 *  $network = $kernel->graph();
 *  print_r($network->members());
 *  ```
 * 
 * @method Services\ServiceInterface config() Returns kernel configuration as a Zend\Config\Config object.
 * @method Services\ServiceInterface events() Returns events broker.
 * @method Services\ServiceInterface logger() Returns Logger service.
 * @method Services\ServiceInterface database() Returns database service.
 * @method Services\ServiceInterface storage() Returns blob storage service.
 *
 * @author Emre Sokullu
 */
class Kernel extends Init 
{
  /**
   * Constructor.
   *
   * @param $settings  array  Service configurations.
   */
  public function __construct( array $settings = [] )
  {
    $GLOBALS["kernel"] = &$this;
    $this->reconfigure( $settings );
    
  }

  public function halt(): void
  {
    if(!$this->is_running) {
      throw new Exceptions\KernetNotRunningException();
    }
    $this->is_running = false;
    unset($GLOBALS["kernel"]);
  }


  /**
   * Sets up the kernel settings.
   * 
   * Configuration variables can be passed to the kernel either
   * at construction (e.g. ```$kernel = new Kernel(...);```)
   * or afterwards, using this function ```$kernel->reconfigure(...);```
   * Please note, this function should be run before calling the
   * *boot* function, or otherwise it will not have an effect
   * and throw the {@link Pho\Kernel\Exceptions\KernelIsAlreadyRunningException} exception.
   *
   * @param array $settings Service configurations.
   *
   * @throws KernelIsAlreadyRunningException When run after the kernel has booted up.
   */
  public function reconfigure( array $settings = [] ): void
  {
    if($this->is_running) {
      throw new Exceptions\KernelAlreadyRunningException("You cannot reconfigure a running kernel.");
    }
    $this["settings"] = $settings;
    $this["config"] = $this->share(function($c) {
        $config =  new \Zend\Config\Config(include __DIR__ . DIRECTORY_SEPARATOR . "Defaults.php");
        $config->merge(new \Zend\Config\Config($c["settings"]));
        return $config;
    });
    $this->is_configured = true;
  }

  /**
   * Initializes the kernel.
   * 
   * Once the configuration is set, run "boot" to start the kernel.
   * Please note, you will not be able to reconfigure the kernel or
   * register new nodes after this point, or you will encounter the
   * {@link Pho\Kernel\Exceptions\KernelIsAlreadyRunningException} 
   * exception.
   *
   * @throws KernelIsAlreadyRunningException When run after the kernel has booted up.
   *
   */
  public function boot(): void
  {
    if($this->is_running) {
      throw new Exceptions\KernelAlreadyRunningException();
    }
    $this->is_running = true;
    $this->setupServices();
    $this["query"] = $this->share(function($c) {
      return new Query($c);
    });
    $this->seedRoot();
    $this->registerListeners($this["graph"]);

    $this->events()->emit("kernel.booted_up");
  }


   public function register(array $classes): void
   {
     $type = function(string $class): string
     {
        if($class instanceof Framework\Actor) return "actor";
        if($class instanceof Framework\Object) return "object";
        if($class instanceof Framework\Frame) return "graph";
     };
     foreach($classes as $class) {
        if(!$class instanceof Framework\ParticleInterface) {
          $this->logger()->warning();
          continue;
        }
        $this->class_registry[$type($class)][] = $class;
     }
   }
}