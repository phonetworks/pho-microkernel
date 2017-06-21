<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services\Storage;

/**
 * Blob storage for the Pho Kernel
 * 
 * This service is used to store and retrieve binary files such as photos and videos. It is by no means a 
 * keeper of truth. Available adapters are:
 * 
 * * Filesystem: Suitable for testing. Can't scale unless the underlying filesystem is distributed (e.g NFS or better yet, glusterfs).
 * * AWS S3: Infinitely scalable cloud based file hosting solution from Amazon AWS.
 * * OpenStack: Open source infinitely scalable on-premise cloud file hosting solution.
 * 
 * @author  Emre Sokullu <emre@phonetworks.org>
 */
interface StorageInterface {

  /**
   * Store a new file
   *
   * @param string $file
   * @param string $path
   * @return void
   */
  public function put(string $file, string $path): void;

  /**
   * Append an existing file.
   *
   * @param string $file
   * @param string $path
   * @return void
   */
  public function append(string $file, string $path): void;

  /**
   * Returns the HTTP URL of an existing file.
   *
   * @param string $path
   * @return void
   */
  public function get(string $path): string;

  /**
   * Creates a new directory.
   *
   * @param string $dir
   * @param bool $recursive Set true if the designated directory contains multiple new folders.
   * @return void
   */
  public function mkdir(string $dir, bool $recursive): void;

  /**
   * Checks if the file does already exist.
   *
   * @param string $path
   * @return void
   */
  public function file_exists(string $path): bool;
}
