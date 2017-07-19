<?php

namespace PhoNetworksAutogenerated;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Traits;
use Pho\Kernel\Foundation;




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

    const FIELDS = "{\"password\":{\"constraints\":{\"minLength\":null,\"maxLength\":null,\"uuid\":null,\"regex\":\"^[a-zA-Z0-9_]{4,12}$\",\"greaterThan\":null,\"lessThan\":null},\"directives\":{\"md5\":true,\"now\":false,\"default\":\"|_~_~NO!-!VALUE!-!SET~_~_|\"}},\"join_time\":{\"constraints\":{\"minLength\":null,\"maxLength\":null,\"uuid\":null,\"regex\":null,\"greaterThan\":null,\"lessThan\":null},\"directives\":{\"md5\":false,\"now\":true,\"default\":\"|_~_~NO!-!VALUE!-!SET~_~_|\"}},\"birthday\":{\"constraints\":{\"minLength\":null,\"maxLength\":null,\"uuid\":null,\"regex\":null,\"greaterThan\":null,\"lessThan\":null},\"directives\":{\"md5\":false,\"now\":false,\"default\":411436800}},\"about\":{\"constraints\":{\"minLength\":null,\"maxLength\":\"255\",\"uuid\":null,\"regex\":null,\"greaterThan\":null,\"lessThan\":null},\"directives\":{\"md5\":false,\"now\":false,\"default\":\"\"}}}";

    public function __construct(\Pho\Kernel\Kernel $kernel, \Pho\Lib\Graph\GraphInterface $graph , string $password, ?int $birthday = 411436800, ?string $about = "")
    {
        $this->registerIncomingEdges(UserOut\Follow::class);
        $this->registerIncomingEdges(StatusOut\Mention::class);
        parent::__construct($kernel, $graph);
                $this->setPassword($password);
        $this->setJoinTime(time());
        $this->setBirthday($birthday);
        $this->setAbout($about);

    }

}

/*****************************************************
 * Timestamp: 1500415734
 * Size (in bytes): 2233
 * Compilation Time: 15704
 * 7c9268c25566020a7b0c8562526bb03c
 ******************************************************/