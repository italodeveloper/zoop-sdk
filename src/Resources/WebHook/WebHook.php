<?php
namespace Zoop\WebHook;
use Zoop\Zoop;

class WebHook extends Zoop 
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    public function createWebHook(string $url, string $description)
    {
        $request = $this->configurations['guzzle']->request(
            'POST', '/v1/marketplaces/'. $this->configurations['marketplace']. '/webhooks',
            ['json' => array(
                'url' => $url,
                'method' => 'POST', 
                'description' => $description
            )]
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return $response;
        }
        return false;
    }

    public function getAllWebHooks()
    {
        $request = $this->configurations['guzzle']->request(
            'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/webhooks',
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return $response;
        }
        return false;
    }

    public function deleteWebHook(string $webhookId)
    {
        $request = $this->configurations['guzzle']->request(
            'DELETE', '/v1/marketplaces/'. $this->configurations['marketplace']. '/webhooks/' . $webhookId,
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return $response;
        }
        return false;
    }
}