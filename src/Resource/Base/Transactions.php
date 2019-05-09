<?php
namespace Zoop\Base;

abstract class Transactions 
{
    /** @var $configuration  */
    protected $configuration;
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getTransaction(string $transaction)
    {
        $request = $this->configuration['guzzle']->request(
            'GET', '/v1/marketplaces/'. $this->configuration['marketplace']. '/transactions/'. $transaction,
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return $response;
        }
        return false;
    }
}