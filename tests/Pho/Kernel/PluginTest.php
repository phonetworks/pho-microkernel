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

class PluginTest extends TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testPluginError() {
        $this->kernel->registerPlugin(new class() {});
    }

    public function testPluginTrue() {
        $this->kernel->registerPlugin(new class() implements PluginInterface { 
            public function name(): string
            {
                return "emre";
            }
            public function do(): bool
            {
                return true;
            }
            public function init(): void
            {

            }
        });
        $this->assertTrue($this->kernel->plugin("emre")->do());
    }

    public function testPluginSimpleTrue() {
        $this->kernel->registerPlugin(new class($this->kernel) extends AbstractPlugin { 
            public function name(): string
            {
                return "emre";
            }
            public function do(): bool
            {
                return true;
            }
        });
        $this->assertTrue($this->kernel->plugin("emre")->do());
    }

}