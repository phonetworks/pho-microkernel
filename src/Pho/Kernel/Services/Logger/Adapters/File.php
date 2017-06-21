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
 * File based logging. The log files specified at the kernel
 * constructor level or via the *configure* function of the kernel.
 *
 * @author Emre Sokullu
 */
class File extends LoggerBase {

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
   *
   * @param string $uri  the path to log file in uri form.
   */
  public function __construct(Kernel $kernel, array $params = []) {
    $this->channel = new Logger('default');
    $this->channel->pushHandler(new StreamHandler($params["path"], Logger::INFO));
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
