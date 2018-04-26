<?php

namespace Pho\Kernel;

use Pho\Kernel\Kernel;
use Pho\Framework;
use Pho\Lib\Graph;
use Pho\Lib\Graph\EntityInterface;
use Pho\Kernel\Foundation;
use Pho\Lib\Graph\ID;

/**
 * Graphsystem
 * 
 * Gs is short for graphsystem; Pho's equivalent of UNIX' filesystem.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Graphsystem 
{
    private $database;
    private $index;
    private $logger;
    private $events;

    /**
     * Keeps track of unserialized nodes
     * 
     * ID(string)=>Object
     *
     * @var array
     */
    protected $node_cache = [];

    /**
     * Keeps track of unserialized edges
     * 
     * ID(string)=>Object
     *
     * @var array
     */
    protected $edge_cache = [];

    public function __construct(Kernel $kernel) {
        $this->database = $kernel->database();
        $this->logger = $kernel->logger();
        $this->index = $kernel->index();
        $this->events = $kernel->events();
        // warmup cache must not be here.
    }

    public function warmUpCache(Kernel $kernel): void
    {
        $this->node_cache[$kernel->space()->id()->toString()] = $kernel->space();
        $this->node_cache[$kernel->graph()->id()->toString()] = $kernel->graph();
        $this->node_cache[$kernel->founder()->id()->toString()] = $kernel->founder();
    }

  /**
   * Retrieves a node
   *
   * @param string $node_id
   * 
   * @return Pho\Lib\Graph\NodeInterface The node object.
   * 
   * @throws Pho\Kernel\Exceptions\NodeDoesNotExistException When there is no entity with the given id.
   * @throws Pho\Kernel\Exceptions\NotANodeException When the given id does not belong to a node.
   */
  public function node(string $node_id): Graph\NodeInterface
  {
    if(isset($this->node_cache[$node_id])) {
      return $this->node_cache[$node_id];
    }
    $query = (string) $node_id; // sprintf("node:%s", (string) $node_id);
    $node = $this->database->get($query);
    if(is_null($node)) {
      throw new Exceptions\NodeDoesNotExistException($node_id);
    }
    $node = unserialize($node);
    if(!$node instanceof Framework\ParticleInterface && !$node instanceof Foundation\World) {
      throw new Exceptions\NotANodeException($node_id);
    }
    $node->registerHandler(
            "set",
            \Pho\Kernel\Foundation\Handlers\Set::class
    );
    if($node instanceof Foundation\AbstractActor) {
        $node->registerHandler(
            "form",
            \Pho\Kernel\Foundation\Handlers\Form::class
        );
    }
    $node->init();
    $this->node_cache[$node_id] = $node;
    
    return $node;
  }

  /**
   * Retrieves an edge
   * 
   * Reconstructs a single edge object based on its ID.
   *
   * @param string $node_id
   * 
   * @return Pho\Lib\Graph\EdgeInterface The edge in its object form.
   * 
   * @throws Pho\Kernel\Exceptions\EdgeDoesNotExistException when the given id does not exist in the database.
   * @throws Pho\Kernel\Exceptions\NotAnEdgeException when the given id does not belong to an edge.
   */
  public function edge(string $edge_id): Graph\EdgeInterface
  {
    if(isset($this->edge_cache[$edge_id]))
      return $this->edge_cache[$edge_id];
    $query = (string) $edge_id; // sprintf("edge:%s", (string) $edge_id);
    $edge = $this->database->get($query);
    if(is_null($edge)) {
      throw new Exceptions\EdgeDoesNotExistException($edge_id);
    }
    $edge = unserialize($edge);
    if(!$edge instanceof Graph\EdgeInterface) {
      throw new Exceptions\NotAnEdgeException($edge_id);
    }
    $edge->setup();
    $edge->init();
    $this->edge_cache[$edge_id] = $edge;
    return $edge;
  }

  public function entity(string $entity_id): EntityInterface
  {
    $node = null;
    try {
      $node = $this->node($entity_id);
    }
    catch(\Exception $node_exception) {}
    finally {
      if(!is_null($node))
        return $node;
      try {
        $edge = $this->edge($entity_id);
      }
      catch(\Exception $edge_exception) {
        throw new Exceptions\EntityDoesNotExistException($entity_id);
      }
      return $edge;
    }
  }

  /**
   * Creates the entity in the graphsystem 
   *
   * @param Graph\EntityInterface $entity
   * 
   * @return void
   */
  public function touch(EntityInterface $entity): void
  { 
    $this->logger->info("Touching %s", $entity->id()->toString());
    $this->database->set(
        (string) $entity->id(), serialize($entity)
    );
    $this->logger->info("Indexing %s", $entity->id()->toString());
    $arr = $entity->toArray();
    $this->events->emit("graphsystem.touched", [$arr]);
    //$this->index->index($entity);
  }

  public function delEdge(Graph\ID $id): void
  {
      $this->database->del($id);
      $this->events->emit("graphsystem.edge_deleted", [(string) $id]);
      //$this->index->edgeDeleted((string) $id);
  }

  public function delNode(Graph\ID $id): void
  {
      $this->database->del($id);
      $this->events->emit("graphsystem.node_deleted", [(string) $id]);
      //$this->index->nodeDeleted((string) $id);
  }

  public function expire(Graph\ID $id, int $timeout = (60*60*24)): void
  {
    $this->database->expire((string) $id, $timeout);
  }

}
