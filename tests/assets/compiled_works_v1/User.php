<?php

namespace PhoNetworksAutogenerated;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Traits;
use Pho\Kernel\Foundation;
use Webmozart\Assert\Assert;




/*****************************************************
 * This file was auto-generated by pho-compiler
 * For more information, visit http://phonetworks.org
 ******************************************************/

class User extends Foundation\AbstractActor {

    const T_EDITABLE = false;
    const T_PERSISTENT = true;
    const T_EXPIRATION =  0;
    const T_VERSIONABLE = false;
    
    const DEFAULT_MOD = 0x0e554;
    const DEFAULT_MASK = 0xeeeee;

    public function __construct(\Pho\Kernel\Kernel $kernel, \Pho\Lib\Graph\GraphInterface $graph , string $password, ?int $birthday = 411436800, ?string $about = "")
    {
        parent::__construct($kernel, $graph);
        Assert::regex($password,  "/^[a-zA-Z0-9_]{4,12}$/");
$this->attributes()->password = md5($password);
$this->attributes()->join_time = time();
$this->attributes()->birthday = $birthday;
Assert::maxLength($about, 255);
$this->attributes()->about = $about;

    }

    protected function onIncomingEdgeRegistration(): void
    {
        $this->registerIncomingEdges(UserOut\Follow::class);
        $this->registerIncomingEdges(StatusOut\Mention::class);
    }

}

/*****************************************************
 * Timestamp: 1499811736
 * Size (in bytes): 1536
 * Compilation Time: 3841
 * 0097f6db9b6f752c5dc05199e4b3bfe3
 ******************************************************/