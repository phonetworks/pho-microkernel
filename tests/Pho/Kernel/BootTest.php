<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel;

class BootTest extends \PHPUnit\Framework\TestCase 
{
    protected $kernel;
    protected $redis;

    private function setupRedis(): void
    {
      $config = $this->getKernelConfig();
      $this->redis  = new \Predis\Client($config["services"]["database"]);
    }

    private function getKernelConfig()
    {
        return array(
          "services"=>array(
            "database" => getenv('DATABASE_URI'),
            "storage" => getenv("STORAGE_URI")
          )
        );
    }

    public function setUp() {
      $this->kernel = new Kernel($this->getKernelConfig());
    }

    public function tearDown() {
      if($this->kernel->status()=="on")
        $this->kernel->halt();
      unset($this->kernel);
    }

    public function testSimple() {
        $this->assertInstanceOf(Kernel::class, $this->kernel);        
    }

    public function testBoot() {
      $this->setupRedis();
      $this->kernel->boot();
      $redis_configs = $this->redis->keys("configs:*");
      $this->assertContains("configs:graph_id", $redis_configs);
      $this->assertContains("configs:founder_id", $redis_configs);
      $graph_recreated = $this->kernel->gs()->node($this->redis->get("configs:graph_id"));
      $founder_recreated = $this->kernel->gs()->node($this->redis->get("configs:founder_id"));
      $this->assertInstanceOf(Standards\Founder::class, $founder_recreated);
      $this->assertInstanceOf(Standards\Graph::class, $graph_recreated);
    }
}