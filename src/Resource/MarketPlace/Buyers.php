<?php
namespace Zoop\Marketplace;

class Buyers 
{
    private $configuration;
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function createBuyer(array $user)
    {
        $request = $this->configuration['guzzle']->request(
            'POST', '/v1/marketplaces/'. $this->configuration['marketplace']. '/buyers', 
            ['json' => $user]
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return $response;
        }
        return false;
    }

    public function getAllBuyers()
    {
        $request = $this->configuration['guzzle']->request(
            'GET', '/v1/marketplaces/'. $this->configuration['marketplace']. '/buyers'
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return $response;
        }
        return false;
    }

    public function getBuyer(string $userId)
    {
        $request = $this->configuration['guzzle']->request(
            'GET', '/v1/marketplaces/'. $this->configuration['marketplace']. '/buyers/' . $userId
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return $response;
        }
        return false;
    }
}