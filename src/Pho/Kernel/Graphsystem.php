<?php

namespace Pho\Kernel;

use Pho\Kernel\Kernel;
use Pho\Framework;
use Pho\Lib\Graph;
use Pho\Lib\Graph\EntityInterface;

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
    private $logger;

    public function __construct(Kernel $kernel) {
        $this->database = $kernel->database();
        $this->logger = $kernel->logger();
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
    $query = sprintf("node:%s", (string) $node_id);
    $node = $this->database->get($query);
    if(is_null($node)) {
      throw new Exceptions\NodeDoesNotExistException(sprintf("There is no node registered with the uuid %s", (string) $node_id));
    }
    $node = unserialize($node);
    if(!$node instanceof Framework\ParticleInterface && !$node instanceof Foundation\World) {
      throw new Exceptions\InvalidTypeException(sprintf("The id \"%s\" does not pertain to a Node.", (string) $node_id));
    }
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
    $query = sprintf("edge:%s", (string) $edge_id);
    $edge = $this->database->get($query);
    if(is_null($edge)) {
      throw new Exceptions\EdgeDoesNotExistException(sprintf("No edge with the id: %s", (string) $edge_id));
    }
    $edge = unserialize($edge);
    if(!$edge instanceof Graph\EdgeInterface) {
      throw new Exceptions\NotAnEdgeException(sprintf("The id %s does not belong to a a valid edge entity.", (string) $edge_id));
    }
    return $edge;
  }

  /**
   * Creates the entity in the graphsystem 
   *
   * @param Graph\EntityInterface $entity
   * 
   * @return void
   */
  public function touch(Graph\EntityInterface $entity): void
  {
    $key = "node";
    if($entity instanceof Graph\EdgeInterface) {
      $key = "edge";
    }
    $this->database->set(
        sprintf("%s:%s", $key, (string) $entity->id()), serialize($entity)
    );
  }

  public function delEdge(Graph\ID $id): void
  {
      $this->database->del(sprintf("edge:%s", $id));
  }

  public function delNode(Graph\ID $id): void
  {
      $this->database->del(sprintf("node:%s", $id));
  }

  public function expire(Graph\ID $id, int $timeout = (60*60*24)): void
  {
    $this->database->expire((string) $id, $timeout);
  }

}