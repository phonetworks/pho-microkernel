<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Acl;

use Pho\Framework;

interface AclInterface {
    /**
     * Undocumented function
     *
     * @param int $mod
     * @return void
     */
    public function chmod(int $mod): void;

    public function toArray(): array;

    public function executable(Framework\Actor $actor): bool;

    public function readable(Framework\Actor $actor): bool;

    public function writeable(Framework\Actor $actor): bool;

    // https://www.cyberciti.biz/tips/understanding-linux-unix-umask-value-usage.html
    //public function umask(int $mask): void;

    public function set(string $entity_pointer, string $mode): void;
    public function del(string $entity_pointer): void;

}
