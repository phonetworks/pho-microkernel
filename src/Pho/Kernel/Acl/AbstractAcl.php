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
use Pho\Framework\AclCore;
use Pho\Framework\Actor;
use Pho\Kernel\Foundation;
use Pho\Framework\ParticleInterface;
use Pho\Kernel\Kernel;
use Pho\Lib\Graph\ID;
use Pho\Lib\Graph;

/**
 * Acl (Access Control List)
 * 
 * Uses decorator pattern over Pho\Framework's AclCore class.
 * This is an abstract class and must be extend by particle
 * specific Acl classes. 
 * 
 * The design of this class has been heavily inspired by UNIX
 * ACL as well as Extended ACL.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
abstract class AbstractAcl {
    
    protected $kernel;
    protected $core;
    protected $creator;
    protected $context;
    protected $permissions = ["a::"=>"mrwx"];
    protected $permissions_with_graph = [];
    protected $sticky = false;

    const _ = "-";
    const M = "m"; // manage
    const R = "r";
    const W = "w";
    const X = "x";
    

    const _UUID_REGEXP = '[a-f0-9]{8}\-[a-f0-9]{4}\-4[a-f0-9]{3}\-(8|9|a|b)[a-f0-9]{3‌​}\-[a-f0-9]{12}';

    public function __construct(Kernel $kernel, ParticleInterface $particle, /*int|array*/ $permissions = 0x0e754) {
        $this->kernel = $kernel;
        $this->core = $particle;
        if(is_array($permissions))
            $this->load($permissions);
        else if(is_numeric($permissions))
            $this->chmod($permissions);
        else
            throw new Exceptions\InvalidPermissionSetException($permissions, $this->core->id());
    }

    protected function load(array $permissions): void
    {
        foreach($permissions as $permission) {
            if(!preg_match('/^(([ausgo]):(([a-f0-9\-]+){0,1}):)([mrwx\-]{4})$/i', $permission, $matches)) {
                //eval(\Psy\sh());
                throw new Exceptions\InvalidSerializedAclUnitException((string)$permission, $permissions, $this->core->id());
            }
            $this->_setPermission($matches[1], $matches[5]);
        }
    }


    /**
     * Internal permission setter
     * 
     * **Warning:** This function is for internal use only. To
     * set up a new permission, use the ```set``` function instead.
     * 
     * This does not perform checks, assumes it was done apriori.
     * Do not use this function unless you know what you are doing,
     * use ```set()``` instead.
     * 
     * This function is responsible to make sure the permissions_with_graph 
     * array is filled up properly.
     * 
     * @see set If you need to set permission from outside. 
     *
     * @param string $pointer
     * @param string $mode
     * 
     * @return void
     */
    protected function _setPermission(string $pointer, string $mode): void
    {
        $this->permissions[$pointer] = $mode;
        $pointer_with_special_graph = '/^g:([a-f0-9\-]+):$/i';
        if(preg_match($pointer_with_special_graph, $pointer, $matches)) {
            $this->permissions_with_graph[] = $matches[1];
        }
    }

    public function sticky(): bool
    {
        return (bool) $this->sticky;
    }

    public function stick(): void
    {
        $this->sticky = true;
    }

    public function unstick(): void
    {
        $this->sticky = false;
    }

    public function chmod(int $mod): void
    {
        $this->sticky = (bool) $mod & 0x10000;
        $this->permissions["u::"] = ( $mod & 0x08000 ? self::M : self::_ ) . ( $mod & 0x04000 ? self::R : self::_ ) . ( $mod & 0x02000 ? self::W : self::_  ) . ($mod & 0x01000 ? self::X : self::_ );; // user = 
        $this->permissions["s::"] = ( $mod & 0x00800 ? self::M : self::_ ) . ( $mod & 0x00400 ? self::R : self::_ ) . ( $mod & 0x00200 ? self::W : self::_  ) . ($mod & 0x00100 ? self::X : self::_ );; // subscriber = 
        $this->permissions["g::"] = ( $mod & 0x00080 ? self::M : self::_ ) . ( $mod & 0x00040 ? self::R : self::_   ) . ( $mod & 0x00020 ? self::W : self::_  ) . ( $mod & 0x00010 ? self::X : self::_ ); // graph
        $this->permissions["o::"] = ( $mod & 0x00008 ? self::M : self::_ ) . ( $mod & 0x00004 ? self::R : self::_ ) . ( $mod & 0x00002 ? self::W : self::_  ) . ( $mod & 0x00001 ? self::X : self::_  ); // others
    }

    public function toArray(): array
    {
        $to_id = function(Foundation\User $user): string {
            return (string) $user->id();
        };
        $array = array();
        $array["core"] = (string) $this->core->id();
        $array["permissions"] = array();
        array_walk($this->permissions, function(string $item, string $key) use (&$array) {
            $array["permissions"][] = $key.$item;
        });
        //eval(\Psy\sh());
        return $array;
    }

    /**
     * Retrieves the permissions for this node with the given pointer
     *
     * @param string $entity_pointer The entity pointer
     * 
     * @return string The permissions in string format
     * 
     * @throws Exceptions\NonExistentAclPointer thrown when there is no such pointer for given node.
     */
    public function get(string $entity_pointer): string
    {
        if(isset($this->permissions[$entity_pointer])) {
            return $this->permissions[$entity_pointer];
        }
        throw new Exceptions\NonExistentAclPointer($entity_pointer, (string) $this->core->id());
    }

    public function set(string $entity_pointer, string $mode): void
    {
        $valid_pointer = '/^([usgo]):(([a-f0-9\-]+){0,1}):$/i';
        if(!preg_match($valid_pointer, $entity_pointer, $matches)) {
            throw new Exceptions\InvalidAclPointerException($entity_pointer, $valid_pointer, __FUNCTION__);
        }
        else if(!preg_match("/^[mrwx\-]{4}$/", $mode)) {
            throw new Exceptions\InvalidAclModeException($mode);
        }
        $type = $matches[1];
        $uuid = $matches[2];
        /**
         * @todo
         * * check if uuid points to a actor or graph 
         * * throw exception if $type and actual type don't match
         */
        $this->_setPermission($entity_pointer, $mode);
    }

    // can delete specific users and graphs
    public function del(string $entity_pointer): void
    { 
        $valid_pointer = "/^([ug]):([a-f0-9\-]+):$/i";
        if(!preg_match($valid_pointer, $entity_pointer, $matches)) {
            throw new Exceptions\InvalidAclPointerException($entity_pointer, $valid_pointer, __FUNCTION__);
        }
        $type = $matches[1];
        $uuid = $matches[2];
        // check if uuid points to a actor or graph 
        // throw exception if $type and actual type don't match
        unset($this->permissions[$entity_pointer]);
        if($type=="g")
            unset($this->permissions_with_graph[array_search($uuid, $this->permissions_with_graph)]);
    }

    public function manageable(Actor $actor): bool
    {
        $role = $this->resolveRole($actor);
        return strpos($this->permissions[$role], self::M) !== false;
    }

    public function executable(Actor $actor): bool
    {
        $role = $this->resolveRole($actor);
        return strpos($this->permissions[$role], self::X) !== false;
    }

    public function readable(Actor $actor): bool
    {
        //if(isset($GLOBALS["dur"])&&$GLOBALS["dur"]) eval(\Psy\sh());
        $role = $this->resolveRole($actor);
        return strpos($this->permissions[$role], self::R) !== false;
    }

    public function writeable(Actor $actor): bool
    {
        $role = $this->resolveRole($actor);
        return strpos($this->permissions[$role], self::W) !== false;
    }

   // abstract public function resolveRole(Actor $actor): string;
   public function resolveRole(Framework\Actor $actor): string
    {

        // we can use an algorithm that matches all roles
        // $matches = [];
        // and picks the one with more privileges at the end
        // but we won't since it will cost performance.

        if(is_a($actor, Framework\Admin::class)) {
            return "a::";
        }
        elseif(isset($this->permissions["u:".(string) $actor->id().":"])) {
            return "u:".(string) $actor->id().":";
        }
        elseif($actor->id() == $this->core->creator()->id()) {
            return "u::";
        }

        foreach($this->permissions_with_graph as $graph_uuid) {
            if($actor->hasSubscriber(ID::fromString($graph_uuid))) { // or hasSubscriber
                return "g:".$graph_uuid.":";
            }
        }
        
        if($this->isSubscriber($actor)) {
            return "s::";
        }
        elseif($this->core->context()->contains($actor->id())) {
            return "g::";
        }
        return "o::";
        
    }

    abstract public function isSubscriber(Framework\Actor $actor): bool;

}