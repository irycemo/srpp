<?php

namespace App\AuditResolvers;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class IPResolver implements Resolver
{
    public static function resolve(Auditable $auditable)
    {

        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {

            $ip = getenv("HTTP_CLIENT_IP");

        } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {

            $ip = getenv("HTTP_X_FORWARDED_FOR");

        } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {

            $ip = getenv("REMOTE_ADDR");

        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {

            $ip = $_SERVER['REMOTE_ADDR'];

        } else {

            $ip = "unknown";

        }

        return(substr($ip,0,44));

    }
}
