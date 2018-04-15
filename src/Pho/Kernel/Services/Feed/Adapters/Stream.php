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

}
