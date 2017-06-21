<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Events\Adapters;

use Pho\Kernel\Kernel;
use Sabre\Event\EventEmitter;
use Pho\Kernel\Services\ServiceInterface;
use Pho\Kernel\Services\Events\EventsInterface;
use Pho\Kernel\Services\Events\EventsUtils;
use Pho\Kernel\Services\Events\Exceptions\InvalidEventException;

/**
 * Events Adapter for single-machine setup.
 * 
 * This adapter is suitable for a single machine web app installations only
 * and it is not scalable to heavy load. Therefore it is advised to choose
 * ZeroMQ adapter for use cases with large traffic. You can always start with
 * this adapter and switch to another, more scalable one at any time you want
 * with just a config change and installation of related extensions - no data
 * transfer or import/export would be needed.
 *
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Local extends EventsUtils implements EventsInterface, ServiceInterface {

  /**
   * @var \Pimple
   */
  private $kernel;

  /**
   * @var Sabre\Event\EventEmitter
   */
  private $event_emitter;

  public function __construct(Kernel $kernel, array $params = []) {
    $this->kernel = $kernel;
    $this->event_emitter = new EventEmitter();
  }

  /**
   * {@inheritdoc}
   */
  public function emit(string $event, ?array $properties=null): void
  {
    if(!$this->isValidEvent($event)) {
      throw new InvalidEventException(sprintf("%s is not a valid kernel event.", $event));
    }
    if(!is_null($properties)) {
      $this->event_emitter->emit($event, $properties);
    }
    else {
      $this->event_emitter->emit($event);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function on(string $event, callable $callback): void
  {
    $this->event_emitter->on($event, $callback);
  }

}
