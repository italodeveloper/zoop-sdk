<?php
namespace Zoop;
use Zoop\AuthHelper;

class Transactions 
{
    protected $configuration;
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * getAll function
     * 
     * Lista todas as transações do Marketplace instanciado nas
     * configurações.
     *
     * @return array|bool
     */
    public function getAll()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 
            
            $this->configuration['base_url'] .
            '/v1/marketplaces/'. $this->configuration['marketplace'] .
            '/sellers/'. $this->configuration['auth']['on_behalf_of'] .
            '/transactions?limit='. $this->configuration['configurations']['limit'] .
            '&sort='. $this->configuration['configurations']['sort'] .
            '&offset='. $this->configuration['configurations']['offset'],

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic ' . AuthHelper::getBasic($this->configuration),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Host: api.zoop.ws',
                'accept-encoding: gzip, deflate',
                'cache-control: no-cache',
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return \json_decode($response, true);
        }
    }

    public function get(string $transaction)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration['base_url'] .
            '/v1/marketplaces/'. $this->configuration['marketplace'] .
            '/transactions/'. $transaction,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic ' . AuthHelper::getBasic($this->configuration),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Host: api.zoop.ws',
                'accept-encoding: gzip, deflate',
                'cache-control: no-cache',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return \json_decode($response, true);
        }
    }
}