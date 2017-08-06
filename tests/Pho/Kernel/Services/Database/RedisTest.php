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

use Pho\Kernel\TestCase;

class RedisTest extends TestCase {

 public function testRootMethods() {
      $this->kernel->database()->set("field1", "value");
    $this->assertEquals("value", $this->kernel->database()->get("field1"));
    $this->kernel->database()->del("field1");
    $this->assertNull($this->kernel->database()->get("field1"));
 }

 public function testListMethods() {
     $faker = \Faker\Factory::create();
     $list_name = $faker->word;
    $this->kernel->database()->sadd($list_name, "value1");
    $this->assertCount(1, $this->kernel->database()->smembers($list_name));
    $this->kernel->database()->sadd($list_name, "value2");
    $this->assertCount(2, $this->kernel->database()->smembers($list_name));
    $members = $this->kernel->database()->smembers($list_name);
    sort($members);
    $this->assertEquals(["value1", "value2"], $members);
    $this->assertTrue((bool)$this->kernel->database()->sismember($list_name, "value1"));
    $this->kernel->database()->srem($list_name, "value2");
    $this->assertCount(1, $this->kernel->database()->smembers($list_name));
    $this->kernel->database()->spop($list_name);
    $this->assertCount(0, $this->kernel->database()->smembers($list_name));
    $this->kernel->database()->del($list_name);
  }


  public function testHashMethods() {
    $this->kernel->database()->hset("my_hash", "field1", "value");
    $this->assertEquals("value", $this->kernel->database()->hget("my_hash", "field1"));
    $this->kernel->database()->hdel("my_hash", "field1");
    $this->assertNull($this->kernel->database()->hget("my_hash", "field1"));
  }

}
