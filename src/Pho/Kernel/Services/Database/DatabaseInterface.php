<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Database;


/**
 * Persistent data store service.
 * 
 * Database acts as a persistent storage for the nodes, their attributes
 * and edges. It's a keeper of truth, and may not perform well in complex
 * search queries.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
interface DatabaseInterface {

  /**
   * Stores a value with the given key.
   *
   * @param string $key
   * @param array|string|null $value
   */
  public function set(string $key, $value): void;

  /**
   * Returns the value of the given key or null if there's no
   * such key.
   *
   * @param string $key
   *
   * @return array|string|null
   */
  public function get(string $key);

  /**
   * Deletes a row
   *
   * @param string $key
   */
  public function del(string $key): void;


  /**
   * Purges the given database entry from the database in given seconds.
   * 
   * Also works with list keys.
   *
   * @param string  $key
   * @param int $timeout Timeout in seconds.
   */
  public function expire(string $key, int $timeout): void;

  /**
   * Returns the remaining time for expiration in seconds. 
   * 
   * If no expiration was set, returns -1.
   *
   * @param string  $key
   *
   * @return int  Remaining timeout in seconds or -1 if expiration was not set.
   */
  public function ttl(string $key): int;

  /**
   * Direct access to the database client
   * 
   * Read-only access for debugging and as the client
   * may be reused by the Index service.
   *
   * @return mixed
   */
  public function client()/*: mixed*/;

}
