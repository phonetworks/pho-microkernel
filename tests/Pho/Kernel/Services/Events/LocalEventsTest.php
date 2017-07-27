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

use Pho\Kernel\TestCase;
use Pho\Kernel\Services\Events\Adapters\Local as LocalEvents;
use Pho\Kernel\Services\Events\Exceptions\InvalidEventException;

class LocalEventsTest extends TestCase {

  private $events;
  private $expected_string_1="phonetworks";
  private $expected_string_2="emre sokullu";

  public function setUp() {
    parent::setUp();   
    $this->kernel->events()->on("test.event", function($var1, $var2) {
        $this->assertEquals($this->expected_string_1, $var1);
        $this->assertEquals($this->expected_string_2, $var2);
    });
  }

  public function tearDown() {
    parent::tearDown();
  }

  public function testListen() {
    $this->kernel->events()->emit("test.event", [$this->expected_string_1, $this->expected_string_2]);
  }

  public function testInvalidEvent() {
    $this->expectException(InvalidEventException::class);
    $this->kernel->events()->emit("invalid.event");
  }

}
