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

/**
 * You use this class when you do not want to log anything.
 * It's like logging to /dev/null
 *
 * @author Emre Sokullu
 */
class Silence extends LoggerBase {

  /**
   * Constructor.
   */
  public function __construct(Kernel $kernel, string $uri = "") {}

  /**
   * {@inheritdoc}
   */
  public function warning(...$message): void {}

  /**
   * {@inheritdoc}
   */
  public function info(...$message): void {}

  /**
   * {@inheritdoc}
   */
  public function log(...$message): void {}

}
