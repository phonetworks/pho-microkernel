<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Database\Adapters;

use Pho\Kernel\Kernel;
use Pho\Kernel\Services\ServiceInterface;
use Pho\Kernel\Services\Database\DatabaseInterface;
use Pho\Kernel\Services\Exceptions\UnimplementedMethodException;
use Pho\Kernel\Services\Exceptions\MissingAdapterExtensionException;

/**
 * PHP APCU adapter as a database.
 *
 * Please note, this is not a persistent data store therefore
 * it should be avoided in production. This is an introductional
 * demonstration that should not be used in any real-world use case.
 *
 * @author Emre Sokullu
 */
class Apcu implements DatabaseInterface, ServiceInterface {

  /**
   * @var \Pimple
   */
   private $kernel;

  public function __construct(Kernel $kernel, array $params = []) {
    if(!extension_loaded("apcu"))
      throw new MissingAdapterExtensionException("APCU extension is required to load the Database service.");
    $this->kernel = $kernel;
  }

  /**
   * {@inheritdoc}
   */
  public function set(string $key, $value): void
  {
    apcu_store($key, $value);
  }

  /**
   * {@inheritdoc}
   */
  public function get(string $key)
  {
    $val = apcu_fetch($key, $success);
    if(!$success)
      return null;
    return $val;
  }

  /**
   * {@inheritdoc}
   */
  public function del(string $key): void
  {
    apcu_delete($key);
  }

  /**
   * {@inheritdoc}
   */
  public function smembers(string $list): array
  {
    $members = $this->get($list);
    if(!is_null($members))
      $members = json_decode($members, true);
    else
      $members = [];
    return $members;
  }

  /**
   * {@inheritdoc}
   */
  public function sadd(string $list, string $value): void
  {
    $members = $this->list_members($list);
    $members[] = $value;
    $this->set($list, json_encode($members));
  }

  /**
   * {@inheritdoc}
   */
  public function srem(string $list, string $value): void
  {
    $index=array_search($value, $members);
    if($inde===false)
      return; // do nothing
    unset($members[$index]);
    $this->set($list, json_encode($members));
  }

  /**
   * {@inheritdoc}
   */
  public function expire(string $key, int $timeout): void
  {
    throw new UnimplementedMethodException();
  }

  /**
   * {@inheritdoc}
   */
  public function ttl(string $key): int
  {
    throw new UnimplementedMethodException();
    // return -1;
  }

}
