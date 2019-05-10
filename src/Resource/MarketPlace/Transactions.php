<?php
namespace Zoop\Marketplace;

class Transactions 
{
    /** @var $configuration  */
    protected $configuration;
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * function listAll
     *
     * Lista todas as transações do markeplace porem somentes
     * a do vendedor instanciado em configuraçõa.
     *
     * @return array|bool
     */
    public function listAll()
    {
        $request = $this->configuration['guzzle']->request(
            'GET', '/v1/marketplaces/'. $this->configuration['marketplace']. '/sellers/'. $this->configuration['auth']['on_behalf_of'] . '/transactions?limit=20&sort=time-descending&offset=0',
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return $response['items'];
        }
        return false;
    }

    /**
     * function getTransaction
     *
     * Pega os detalhes da transação informada via parametro
     *
     * @param string $transaction
     * @return bool|array
     */
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