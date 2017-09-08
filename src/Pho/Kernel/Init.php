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
 * Kernel Init
 *
 * Contains kernel initialization methods.
 *  
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Init extends Container 
{
  /**
   * @var boolean
   */
  protected $is_running = false;

  /**
   * @var bool
   */
  protected $is_configured = false;

  /**
   * @var array
   */
  protected $class_registry = [];



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
        $config =  new \Zend\Config\Config(include __DIR__ . DIRECTORY_SEPARATOR . "defaults.php");
        $config->merge(new \Zend\Config\Config($c["settings"]));
        return $config;
    });
    $this["space"] = $this->share(function($c) {
         $space_class = $c["config"]->default_objects->space;
        return new $space_class($c);
       });
    $this->is_configured = true;
  }


  /**
    * Sets up kernel services.
    *
    * Private method that readies kernel services according to user settings and system
    * defaults. The services don't initialize right away but start when requested.
    */
   protected function setupServices(): void
   {
       $service_factory = new Services\ServiceFactory($this);
       foreach($this->config()->services->toArray() as $key => $service) {
        var_dump('Try create new service:'. $key. ' |'.$service);
           $this[$key] = $this->share( function($c) use($key, $service, $service_factory) {
             $parameters = parse_url($service); // first parameter scheme, the rest optional ones.
             try {
               return $service_factory->create($key, array_shift($parameters), $parameters); 
             }
             catch(AdapterNonExistentException $e) {
              $this->logger()->warning("The service %s - %s does not exist.", $key, $service);
             }
        });
       }
   }


   /**
    * Registers listeners that are default to the kernel.
    *
    * @param AbstractContext $graph The graph object to start traversal from.
    */
    protected function registerListeners(Graph\GraphInterface $graph): void
   {
     $this->logger()->info("Registering listeners.");
     $nodes = array_values($graph->members());
     $node_count = count($nodes);

     $this->logger()->info(
       "Total # of nodes for the graph \"%s\": %s", $graph->id(), (string) $node_count
      );

     for($i=0; $i<$node_count; $i++) {
       $node = $nodes[$i];
       $this->logger()->info(
         "Registering listeners for node %s, a %s", $node->id(), $node->label()
        );

       $ref = new \ReflectionObject($node);
       $ref_methods = $ref->getMethods( \ReflectionMethod::IS_PUBLIC );

       array_walk($ref_methods, function($item, $key) use ($node) {
         if(preg_match("/^handle([A-Z][a-z]+)([A-Z][a-z]+)$/",$item->name,$item_parts)) {

           $this->logger()->info(
             "Adding a listener on %s with id %s", $node->label(), $node->id()
           );

           $this->events()->on(
            strtolower($item_parts[1].".".$item_parts[2]), [$node, $item->name]
          );
         }
       });

       // recursiveness
       if($node instanceof Graph\GraphInterface and $node->id() != $graph->id())   {
         $this->registerListeners($node);
       }

       // memory management.
       // clean up after use.
       unset($nodes[$i]); // $nodes[$i] = null;
       // gc_collect_cycles(); // perhaps give the user option to choose Fast Boot vs Memory Controlled Boot (good for resource-constrainted distributed systems that may tolerate slow boot)

     }
   }


   /**
    * Ensures that there is a root Graph attached to the kernel.
    * Used privately by the kernel.
    *
    * @param ?Foundation\AbstractActor The founder object or null (to set 
    * it up with default values or retrieve from the database)
    *
    * @return void
    */
   protected function seedRoot(? Foundation\AbstractActor $founder = null): void
   {  
       $graph_id = $this->database()->get("configs:graph_id");

       if(isset($graph_id)) {
         $this->logger()->info(
           "Existing network with id: %s", 
           $graph_id
         );
         $this["graph"] = $this->share(function($c) use($graph_id) {
            return $c["gs"]->node($graph_id);
          });
           $this["founder"] = $this->share(function($c) { 
           $founder = $c["graph"]->getFounder();
           return $founder;
         });
       }
       else {
          $this->logger()->info("Creating a new graph from scratch");
          $this["founder"] = $this->share(function($c) use ($founder) {
              if(!is_null($founder)) {
                  $founder->persist();
                  return $founder;
              }
              $founder_class = $c["config"]->default_objects->founder;
              return new $founder_class($c); // will turn into admin by Network
          });
          $this["graph"] = $this->share(function($c) {
            $graph_class = $c["config"]->default_objects->graph;
            $graph = new $graph_class($c, $c["founder"], $c["space"], $c["founder"]);
            $c["logger"]->info("Changing founder context as graph");
            $c["founder"]->changeContext($graph);
            //$c["founder"]->hydrate($c, $c["graph"]); // // to make sure that is set up, since the services are now available.
            return $graph;
          });
         $this->database()->set("configs:graph_id", $this->graph()->id());
         $this->logger()->info(
           "New graph with id: %s and founder: %s", $this->graph()->id(), $this->founder()->id()
         );
       }
       
   }
}