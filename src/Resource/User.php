<?php
namespace Zoop;
class User 
{
    private $configuration;
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function createUser(array $user)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration['base_url'] . '/v1/marketplaces/'. $this->configuration['marketplace']. '/buyers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \json_encode($user),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic ' . AuthHelper::getBasic($this->configuration),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Content-Type: application/json',
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
            return json_decode($response, true);
        }
    }

    public function getAllMarketPlace()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration['base_url'] . '/v1/marketplaces/'. $this->configuration['marketplace'] . "/buyers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic '. AuthHelper::getBasic($this->configuration),
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

    public function getUser(string $userId)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration['base_url'] . '/v1/marketplaces/'. $this->configuration['marketplace'] . '/buyers/' . $userId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic '. AuthHelper::getBasic($this->configuration),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Host: api.zoop.ws',
                'accept-encoding: gzip, deflate',
                'cache-control: no-cache'
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