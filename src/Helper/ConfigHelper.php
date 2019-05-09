<?php
namespace Zoop;
use GuzzleHttp\Client;
use Zoop\AuthHelper;

class ConfigHelper 
{
    public static function createConfig(string $token, string $marketplace, string $vendedor)
    {
        return array(
            'marketplace' => $marketplace,
            'gatway' => 'zoop',
            'base_url' => 'https://api.zoop.ws',
            'auth' => array(
                'on_behalf_of' => $vendedor,
                'token' => $token
            ),
            'configurations' => array(
                'limit' => 20,
                'sort' => 'time-descending',
                'offset' => 0,
                'date_range' => null,
                'date_range[gt]' => null,
                'date_range[gte]' => null,
                'date_range[lt]'=> null,
                'date_range[lte]' => null,
                'reference_id'=> null,
                'status' => null,
                'payment_type' => null,
            ),
            'guzzle' => new Client([
                'base_uri' => 'https://api.zoop.ws',
                'timeout' => 2.0,
                'headers' => [
                    'Authorization' => 'Basic ' . AuthHelper::getBasic(['auth' => ['token' => $token]])
                ]
            ])
        );
    }   
}