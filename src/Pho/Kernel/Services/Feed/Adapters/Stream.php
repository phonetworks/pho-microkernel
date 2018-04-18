<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Feed\Adapters;

use Pho\Kernel\Kernel;
use Pho\Kernel\Services\ServiceInterface;
use Pho\Kernel\Services\Feed\FeedInterface;
use GetStream\Stream\Client;

/**
 *
 * @author Emre Sokullu
 */
class Stream implements ServiceInterface, FeedInterface {

  const WALL = "wall";
  const TIMELINE = "timeline";

  /**
   * @var \Pimple
   */
   private $kernel;

   private $client;

  /**
   * Constructor.
   *
   * @param string $uri  the path to log file in uri form.
   */
  public function __construct(Kernel $kernel, string $uri = "") {
    list($id, $secret) = explode(":", $uri, 2);
    $this->client = new Client($id, $secret);
  }

  public function __call(string $func, array $vars) //: mixed
  {
    return $this->client->$func(...$vars);
  }

  protected function instance(EntityInterface $entity, bool $write = false)
  {
    if($write)
      return $this->client->feed(
        self::WALL, 
        (string) $entity->id()
      );
    else // subscribe
      return $this->client->feed(
        self::TIMELINE, 
        (string) $entity->id()
      );
  }

  public function add(EntityInterface $entity): void
  {
    if($entity instanceof AbstractActor)
    {
        $this->instance($entity, true)->addActivity([
            "actor"  => (string) $entity->id(), // actor id
            "verb"   => "_construct", // edge
            "object" => "", // object id
            "_text"  => $entity->feedUpdate()
        ]);
    }
    elseif($entity instanceof Subscribe)
    {
      if(!$entity instanceof Write)
      {
          // bi de follow
      }
      $this->instance($entity->tail(), true)->addActivity([
            "actor"=> (string) $entity->tail()->id(), // actor id
            "verb"=> $entity->label(), // edge
            "object"=> (string) $entity->head()->id(), // object id
            "_text"=>""
      ]);
    }
    
    else {
       // throw exception
    }
  }

  public function follow(ParticleInterface $subject, ParticleInterface $object): void
  {

  }

}
