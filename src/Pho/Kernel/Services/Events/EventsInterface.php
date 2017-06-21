<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Events;

interface EventsInterface {

  /**
   * Broadcasts an event signal with the given name and properties. Listeners,
   * if there's any, would pick up this signal and process it.
   *
   * @param string  $event  Event name. Must be defined in the EventsUtils class.
   * @param array|null  $properties The parameters to pass on to the listener.
   *
   * @throws InvalidEventException If the event is not defined in the EventsUtils class.
   */
  public function emit(string $event, ?array $properties): void;

  /**
   * Creates a listener for the given event name, and defines the callback
   * function to run in case of event trigger.
   *
   * @param string  $events Event name.
   * @param callable  $callback Methods to run in case of event trigger.
   */
  public function on(string $event, callable $callback): void;

}
