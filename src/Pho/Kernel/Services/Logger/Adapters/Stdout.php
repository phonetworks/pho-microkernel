<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Logger\Adapters;

use Pho\Kernel\Kernel;
use Pho\Kernel\Services\Logger\LoggerBase;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Logs to the standard output, the terminal (or console)
 *
 * @author Emre Sokullu
 */
class Stdout extends LoggerBase {

  /**
   * @var \Pimple
   */
   private $kernel;

  /**
   * @var Monolog\Logger
   */
  private $channel;

  /**
   * Constructor.
   */
  public function __construct(Kernel $kernel, array $params = []) {
    $this->channel = new Logger('default');
    $this->channel->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
    $this->kernel = $kernel;
  }

  /**
   * {@inheritdoc}
   */
  public function log(...$message): void
  {
    $this->warning($this->processParams($message));
  }

  /**
   * {@inheritdoc}
   */
  public function warning(...$message): void
  {
    $this->channel->warning($this->processParams($message));
  }

  /**
   * {@inheritdoc}
   */
  public function info(...$message): void
  {
    $this->channel->info($this->processParams($message));
  }

}
