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
      $this->kernel->halt();
      unset($this->kernel);
    }

    public function testSimple() {
        $this->assertInstanceOf(Kernel::class, $this->kernel);
        $this->kernel->boot();
    }
}