<?php
namespace Zoop\Marketplace;

use Zoop\Zoop;
/**
 * Transactions class
 * 
 * Essa classe é responsavel por cuidar das transações do vendedor
 * dentro do marketplace e pode ser utilizada para consultar por exemplos
 * boletos (Tickets).
 * 
 * @package Zoop\Marketplace
 * @author italodeveloper <italo.araujo@gmail.com>
 * @version 1.0.0
 */
class Transactions extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    /**
     * function getAllTransactions
     *
     * Pega todas as transações do vendedor dentro
     * do marketplace
     *
     * @return bool|array
     * @throws \Exception
     */
    public function getAllTransactions()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/sellers/' . $this->configurations['auth']['on_behalf_of'] .'/transactions'
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return $response;
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }

    /**
     * getTransaction function
     *
     * Pega os detalhes de uma transação em especifico
     * utilizando como parametro o id da mesma.
     *
     * @param string $transaction
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getTransaction($transaction)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transactions/'. $transaction
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return $response;
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }
}