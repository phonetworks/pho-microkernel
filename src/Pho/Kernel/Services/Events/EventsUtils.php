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

/**
 * Provides helper methods to the Events adapter classes.
 *
 * @author Emre Sokullu
 */
class EventsUtils {

  /**
   * @var array
   */
  protected $valid_events = [
    "test.event",
    "kernel.booted_up",
    // "kernel.configured",
    "kernel.halted",
    "edge.created",
    "edge.deleted",
    "node.created",
    "node.modified",
    "user.followed",
    "content.posted",
    "object.posted",
    "object.drafted",
    "group.created",
    "group.joined",
    "group.left",
    "edge.set_for_expiration",
    "node.set_for_expiration",
    "user.reacted",
    "graphsystem.touched",
    "graphsystem.node_deleted",
    "graphsystem.edge_deleted",
  ];

  /**
   * Checks if the given event is a valid, kernel-recognized one.
   *
   * @todo Make the list extensible.
   *
   * @param string  $event
   *
   * @return bool Whether the event name is valid or not.
   */
  protected function isValidEvent(string $event): bool
  {
     return in_array($event, $this->valid_events);
  }

  /**
   * Returns a list of all available events. May be used for debugging purposes. 
   *
   * @return array An array of strings (array<string>) with valid event names.
   */
  public function listValidEvents(): array
  {
    return $this->valid_events;
  }

}
