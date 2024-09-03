<?php

namespace GrimPirate\Halberd\Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Shield\TOTP;

class Services extends BaseService
{
    public static function totp(bool $getShared = true): TOTP
    {
        if($getShared)
            return self::getSharedInstance('totp');

        return new TOTP();
    }
}