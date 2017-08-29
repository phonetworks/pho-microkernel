<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Index;

use Pho\Kernel\TestCase;
use Pho\Kernel\Kernel;
use Pho\Kernel\Services\Index;
use Pho\Kernel\Services\Index\Adapters\Elasticsearch;

class IndexingTest extends TestCase {

  protected $kernel;

  public function setUp() {

    $this->kernel = new Kernel($this->getKernelConfig());
    parent::setUp();
  }

  public function tearDown() {
      parent::tearDown();
  }

  public function testIndex()
  {
      $this->kernel->index(['host' => 'http://127.0.0.1:9200/']);
      var_dump("\r\n here \r\n");
      var_dump($this->kernel['index']);
      $this->assertSame('q', 'q');
  }

}
