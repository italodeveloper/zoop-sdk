<?php
namespace Zoop;
class AuthHelper
{
    public static function getBasic(array $configuration)
    {
        return \base64_encode($configuration['auth']['token'] . ':');
    }
}