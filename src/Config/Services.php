<?php

namespace GrimPirate\Halberd\Config;

use CodeIgniter\Config\BaseService;
use GrimPirate\Halberd\Halberd;

class Services extends BaseService
{
    public static function halberd(bool $getShared = true): Halberd
    {
        if($getShared)
            return self::getSharedInstance('halberd');

        return new Halberd();
    }
}