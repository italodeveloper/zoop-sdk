<?php
namespace Zoop\MarketPlace;

use Zoop\Zoop;
/**
 * Class Buyers
 * 
 * Essa classe é resposavel por lidar com os usuarios
 * dentro do marketplace ao nivel do marketplace zoop.
 * 
 * @method \Zoop\MarketPlace\Buyers createBuyer(array $user)
 * @method \Zoop\MarketPlace\Buyers getBuyer(string $userId)
 * @method \Zoop\MarketPlace\Buyers getAllBuyers()
 * 
 * @package Zoop\MarketPlace
 * @author italodeveloper <italoaraujo788@gmail.com>
 * @version 1.0.0
 */
class Buyers extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    /**
     * createBuyer function
     * 
     * Cria o usuario dentro do markeplace ('não é associado ao vendedor')
     * @param array $user
     * @return bool|array
     */
    public function createBuyer(array $user)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'POST', '/v1/marketplaces/'. $this->configurations['marketplace']. '/buyers', 
                ['json' => $user]
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
     * getAllBuyers function
     *
     * Lista todos os usuarios do marketplace
     * ('não realiza associação com o vendedor')
     *
     * @return bool|array
     */
    public function getAllBuyers()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/buyers'
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
     * function getBuyer
     *
     * Pega os dados do usuario associado
     * ao id passado como parametro.
     *
     * @param string $userId
     * @return bool|array
     */
    public function getBuyer(string $userId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/buyers/' . $userId
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