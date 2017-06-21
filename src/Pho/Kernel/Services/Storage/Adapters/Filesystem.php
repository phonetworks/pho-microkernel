<?php

/*
* This file is part of the Pho package.
*
* (c) Emre Sokullu <emre@phonetworks.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Pho\Kernel\Services\Storage\Adapters;

use Pho\Kernel\Kernel;
use Pho\Kernel\Services\ServiceInterface;
use Pho\Kernel\Services\Storage\StorageInterface;
use Pho\Kernel\Services\Storage\Exceptions\InaccessiblePathException;

/**
* Filesystem Adapter for Storage
*
* Filesystem is a simple adapter suitable to to single-machine installations,
* therefore it is not scalable. Refer to OpenStack Swift or AWS S3 as a distributed
* scalable and highly available storage adapter.
*
* @author Emre Sokullu
*/
class Filesystem implements StorageInterface, ServiceInterface
{
    
    /**
     * The root directory to store and retrieve files.
     *
     * @var string
     */
    private $root;
    
    /**
     * Stateful kernel to access services such as Logger.
     *
     * @var Kernel
     */
    private $kernel;
    
    /**
     * Constructor.
     * 
     * @param Kernel $kernel The Pho Kernel to access services
     * @param array $params Must include the directory to store files with the key "path". For example /var/pho/storage (for UNIX) or c:\Pho\Storage (for Windows)
     */
    public function __construct(Kernel $kernel, array $params = [])
    {
        $this->kernel = $kernel;
        if (!isset($params["path"])) {
            $this->root = $kernel->config()->tmp_path . DIRECTORY_SEPARATOR . "pho";
        } else {
            $this->root = $params["path"];
        }
        if (!file_exists($this->root)&&!mkdir($this->root)) {
            throw new InaccessiblePathException($this->root);
        }
        $this->kernel->logger()->info(
        sprintf("The storage service has started with the %s adapter on %s.", __CLASS__, $this->root)
        );
    }
    
    /**
    * {@inheritdoc}
    */
    public function get(string $path): string
    {
        return $this->path_normalize($this->root."/".$path);
    }
    
    /**
    * {@inheritdoc}
    */
    public function mkdir(string $dir, bool $recursive = true): void
    {
        if (!mkdir($this->get($dir), 777, $recursive)) {
            throw new InaccessiblePathException($dir);
        }
    }
    
    /**
    * {@inheritdoc}
    */
    public function file_exists(string $path): bool
    {
        return file_exists($this->get($path));
    }
    
    /**
    * {@inheritdoc}
    */
    public function put(string $file, string $path): void
    {
        file_put_contents($this->get($path), file_get_contents($file), LOCK_EX);
    }
    
    /**
    * {@inheritdoc}
    */
    public function append(string $file, string $path): void
    {
        file_put_contents($this->get($path), file_get_contents($file), LOCK_EX|FILE_APPEND);
    }
    
    
    /**
    * A private method that helps translate directory definition conforming to the operating system settings.
    *
    * @param string $path
    * @return void
    */
    private function path_normalize(string $path): string
    {
        return str_replace(["/","\\"], DIRECTORY_SEPARATOR, $path);
    }
}