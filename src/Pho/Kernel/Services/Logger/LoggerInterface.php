<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Logger;

interface LoggerInterface {

  /**
   * Logs warning or more critical messages. This is same as warning function.
   *
   * @param ...string  $message  If a single parameter is passed, treats as a string, otherwise acts as sprintf
   */
  public function log(...$message): void;

  /**
   * Logs warning or more critical messages.
   *
   * @param ...string  $message  If a single parameter is passed, treats as a string, otherwise acts as sprintf
   */
  public function warning(...$message): void;

  /**
   * Logs informational or debug messages.
   *
   * @param ...string  $message  If a single parameter is passed, treats as a string, otherwise acts as sprintf
   */
  public function info(...$message): void;
}
