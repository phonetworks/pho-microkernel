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
   * Keeps a list of plugins to initialize at boot-up
   *
   * @var array 
   */
  protected $plugins;

  /**
   * A constant used to define how particles in attributebags will be 
   * serialized and stored in the database.
   * 
   * * First value is the prefix.
   * * Last is the suffix.
   * * The ID must be in between the two.
   */
  const PARTICLE_IN_ATTRIBUTEBAG_TPL = ["~|~pho_particle~|~", "~|~"];

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
      throw new Exceptions\KernelNotRunningException();
    }
    $this->is_running = false;
    unset($GLOBALS["kernel"]);
  }

  /**
   * Retrieves the current status of the kernel
   *
   * It's "on" if the kernel is running, "off" if it is not.
   * 
   * @todo add more options such as "shutting down", "frozen" etc.
   * 
   * @return string "on" or "off"
   */
  public function status(): string
  {
    return $this->is_running ? "on" : "off";
  }

  /**
   * Retrieves the current status of the kernel in boolean
   * 
   * True if it's on, false otherwise.
   * 
   * @return bool
   */
  public function live(): bool
  {
    return (bool) $this->is_running;
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
   * @param ?Foundation\AbstractActor The founder object or null (to set 
   * it up with default values or retrieve from the database)
   *
   * @return void
   *
   * @throws KernelIsAlreadyRunningException When run after the kernel has booted up.
   *
   */
  public function boot(? Foundation\AbstractActor $founder = null): void
  {
    if($this->is_running) {
      throw new Exceptions\KernelAlreadyRunningException();
    }
    $GLOBALS["kernel"] = &$this; // one more time, yes.
    $this->is_running = true;
    
    $this->setupServices();
    $this["gs"] = $this->share(function($c) {
      return new Graphsystem($c);
    });
    
    $this["logger"]->info("Services set up. Root seed begins.");
    
    $this->seedRoot($founder);
    
    $this["logger"]->info("Root seeded");
    $this->registerListeners($this["graph"]);
    $this->initPlugins();
    
    $this->events()->emit("kernel.booted_up");
    $this["logger"]->info("Boot complete.");
  }

  public function registerPlugin(/*mixed<string|PluginInterface>*/ $plugin): void
  {
    if($plugin instanceof PluginInterface) {
      $this->plugins[$plugin->name()] = $plugin;
      return;
    }
    elseif(is_string($plugin)&&class_exists($plugin)) {
      $o = new $plugin($this);
      if(!$o instanceof PluginInterface)
        throw new \Exception("Invalid Plugin");
      return;
    }
    throw new \Exception("Invalid Plugin");
  }

  public function plugin(string $name): PluginInterface
  {
    if(isset($this->plugins[$name]))
      return $this->plugins[$name];
     throw new \Exception(sprintf("No plugin with the name %s", $name)); 
  }

}