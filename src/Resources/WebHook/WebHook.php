<?php
namespace Zoop\WebHook;
use Zoop\Zoop;
class WebHook extends Zoop 
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    /**
     * createWebHook function
     *
     * Criando WebHook com cenario "advinhado" pois o mesmo
     * não se encontra na documentação oficial, passando a url
     * de callback e a descrição do hook
     * 
     * @param string $url
     * @param string $description
     * @return array|bool
     * @throws \Exception Erro basico de requisição
     */
    public function createWebHook($url, $description)
    {
        try {
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
        } catch (\Exception $e){
            return $this->ResponseException($e);
        }
    }

    /**
     * getAllWebHooks function
     *
     * Lista todos os webhooks adicioandos ao projeto
     * 
     * @return array|bool
     * @throws \Exception Erro basico de requisição
     */
    public function getAllWebHooks()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/webhook'
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
     * deleteWebHook function
     *
     * Deleta o webhook criado no projeto passando como parametro
     * o id do mesmo (retornado na criação).
     * 
     * @param string $webhookId
     * @return array|bool
     * @throws \Exception Erro basico de requisição
     */
    public function deleteWebHook($webhookId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'DELETE', '/v1/marketplaces/'. $this->configurations['marketplace']. '/webhooks/' . $webhookId
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