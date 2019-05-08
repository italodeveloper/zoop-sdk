<?php
use Zoop\ConfigHelper;
namespace Zoop;

class BasicAuth 
{
    public static function auth(string $token, string $marketplace, string $vendedor)
    {
        return ConfigHelper::createConfig($token, $marketplace, $vendedor);
    }
}