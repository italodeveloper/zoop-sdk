<?php
namespace Zoop\WebHook;

use Zoop\Zoop;

class WebHook extends Zoop 
{
    /**
     * WebHook constructor.
     *
     * @param array $configurations
     */
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
        $this->configurations = $configurations;
    }

    /**
     * validatePayload function
     *
     * Valida dados basicos da resposta recebida da zoop
     * garantindo que a mesma é uma resposta valida para
     * recebimento do evento.
     *
     * @param array $payload
     * @return bool
     */
    private function validatePayload(array $payload)
    {
        $payload = \json_decode($payload, TRUE);
        if(isset($payload)
            && !empty($payload)
            && \is_array($payload)
        ) {
            if (isset($payload['id'])
                && isset($payload['type'])
                && isset($payload['resource'])
                && isset($payload['payload'])
                && isset($payload['payload']['object']['status'])
            ) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * resumePayload funtion
     *
     * Recebe os dados do payload e preenche somente os campos
     * realmente necessarios para se tomar ações dentro da aplicação.
     *
     * @param array $payload
     * @return array
     */
    private function resumePayload(array $payload)
    {
        $payloadReturn = array(
            'event' => array(
                'id' => $payload['id'],
                'type' => $payload['type']
            ),
            'payment' => array(
                'id' => $payload['payload']['object']['id'],
                'type' => $payload['payload']['object']['payment_type'],
                'amount' => $payload['payload']['object']['amount'],
            )
        );
        if(isset($payload['payload']['object']['reference_id'])
            && !empty($payload['payload']['object']['reference_id'])){
                $payloadReturn['referenceId'] = $payload['payload']['object']['reference_id'];
        }
        return $payloadReturn;
    }

    /**
     * webHookListen function
     *
     * Escuta as possiveis chamadas json, valida as mesma
     * (valida como um evento valido) e resume o evento,
     * para que você possa tomar a decisão correta baseado
     * no resultado do mesmo, exemplo, pagamento aprovado
     * ou paagemnto reprovado.
     *
     * @return array|bool|false|string
     */
    public function webHookListen()
    {
        $payload = \file_get_contents('php://input');
        if($this->validatePayload($payload)){
            $payload = $this->resumePayload($payload);
            return $payload;
        }
        //O Payload não é valido, exibe resposta padrão de exibição
        return false;
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