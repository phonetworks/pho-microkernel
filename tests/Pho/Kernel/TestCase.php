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

      include("tests/assets/compiled/Graph.php");
      include("tests/assets/compiled/User.php");
      include("tests/assets/compiled/Status.php");
      include("tests/assets/compiled/StatusOut/Mention.php");
      include("tests/assets/compiled/UserOut/Follow.php");
      include("tests/assets/compiled/UserOut/Like.php");
      include("tests/assets/compiled/UserOut/Post.php");

use Pho\Kernel\Kernel;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $kernel, $redis, $configs, $network;

    protected $created = [];

    /**
     * @var array
     */
    private $log_db_entries = [];

    protected function getKernelConfig()
    {
        return array(
          "services"=>array(
            "database" => getenv('DATABASE_URI'),
            "storage" => getenv("STORAGE_URI")
          )
        );
    }

    protected function startKernel($founder=null): void
    {
      $this->configs = $this->getKernelConfig();
      $this->kernel = new Kernel($this->configs);
      $this->kernel->boot($founder);
      $this->graph = $this->kernel->graph();
      $this->kernel->logger()->info("Test started for: %s", get_class($this));
    }

    protected function stopKernel(): void
    {
      unset($this->redis);
      unset($this->configs);
      unset($this->graph);
      $this->kernel->halt();
      unset($this->kernel);
    }

    protected function setupRedis(): void
    {
      $config = $this->getKernelConfig();
      if(substr($config["services"]["database"],0,8)=="redis://") {
        $this->redis  = new \Predis\Client($config["services"]["database"]);
      }
    }

    public function setUp() {
      $this->startKernel();
      $this->kernel->logger()->info("Kernel started for a test in %s", get_class($this));
    }

    public function tearDown() {
      foreach($this->created as $c) {
            $this->kernel->gs()->delNode($c);
      }
      $this->stopKernel();
    }

     protected function flushDBandRestart()
    {
        $this->kernel->database()->flushdb();
        $this->stopKernel();
        $this->startKernel();
    }

}
