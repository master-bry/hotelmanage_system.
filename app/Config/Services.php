<?php
namespace Config;

use CodeIgniter\Config\Services as CoreServices;
use App\Services\BrevoService;

class Services extends CoreServices
{
    public static function brevo($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('brevo');
        }
        return new BrevoService();
    }
}