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
    $this->gs()->init(); // now that the services are ready, it may start
    
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

  public function flush(): void
  {
    if(!$this->live())
      return; // may throw an exception
    // database: flushdb
    // index: match (n) detach delete n;
  }

  /** 
   * Imports from a given Pho backup file
   *
   * @see Kernel::export()     
   * 
   * @param string $blob 
   * 
   * @return void
   */
  public function import(string $blob): void
  {
    if(!$this->is_running) {
      throw new Exceptions\KernelNotRunningException();
    }
    $import = unserialize($blob);
    foreach($import as $key=>$value) {
        $this->database()->restore($key, 0, $value);
    }
    // rebuild index
    // restart kernel
  }

  /**
   * Reindexed the graph
   *
   * @param boolean $flush true by default. Whether to flush existing index.
   * @return void
   */
  public function reindex(bool $flush = true): void
  {
    if(!$this->is_running) {
      throw new Exceptions\KernelNotRunningException();
    }

    if($flush)
      $this->index()->flush();

    $nodes = array_values($graph->members());
    $node_count = count($nodes);
    $indexed_edges = [];

    for($i=0; $i<$node_count; $i++) {
      $this->gs()->index($nodes[$i]);
      $edges = $nodes[$i]->edges()->out();
      foreach($edges as $edge) {
        if(!in_array($edge->id()->toString(), $indexed_edges)) {
          $indexed_edges[] = $edge->id()->toString();
          $this->gs()->index($edge);
        }
      }
    }
  }

  /**
   * Exports in Pho backup file format
   *
   * There was a problem with json_encode in large files
   * hence switched to php serialize format instead.
   *
   * @see Kernel::import
   * 
   * @return string 
   */
  public function export(): string
  {
    if(!$this->is_running) {
      throw new Exceptions\KernelNotRunningException();
    }
    $return = [];
    $keys = $this->database()->keys("*");
    foreach($keys as $key) {
      // if($key!="index") // skip list
          $return[$key] = $this->database()->dump($key);
    }
    return serialize($return);
  }

}
