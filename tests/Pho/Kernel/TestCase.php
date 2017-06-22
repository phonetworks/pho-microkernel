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

use Pho\Kernel\Kernel;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $kernel, $redis, $configs, $network;

    /**
     * @var array
     */
    private $log_db_entries = [];

    private function getKernelConfig()
    {
        return array(
          "services"=>array(
            "database" => getenv('DATABASE_URI'),
            "storage" => getenv("STORAGE_URI")
          )
        );
    }

    protected function startKernel(): void
    {
      $this->configs = $this->getKernelConfig();
      $this->kernel = new Kernel($this->configs);
      $this->kernel->boot();
      $this->network = $this->kernel->graph();
    }

    protected function stopKernel(): void
    {
      unset($this->redis);
      unset($this->configs);
      unset($this->network);
      $this->kernel->halt();
      unset($this->kernel);
    }

    protected function setupRedis(): void
    {
      $config = $this->getKernelConfig();
      if($config["services"]["database"]["type"]=="redis") {
        $this->redis  = new \Predis\Client($config["services"]["database"]["uri"]);
      }
    }

    public function setUp() {
      $this->startKernel();
      $this->kernel->logger()->info("Kernel started for a test in %s", get_class($this));
    }

    public function tearDown() {
      $this->stopKernel();
    }

}
