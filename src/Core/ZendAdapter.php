<?php
namespace Zoop\Core;

/**
 * ZendAdapter class
 * 
 * Essa classe realiza em formato Fluente assum como o Guzzle
 * a adaptação das requests realizadas com Guzzle para o Zend Http
 * utilziando as PSR'S corretamentes isso fica simples e eficaz.
 * 
 * @package Zoop\Core
 * @author italodeveloper <italoaraujo788@gmail.com>
 * @version 1.0.0
 */
class ZendAdapter 
{
    /** @var $guzzle */
    protected $guzzle;
    public function __construct(array $guzzle)
    {
        $this->guzzle = $guzzle;
    }  
    
    /**
     * request function
     *
     * Simula a request do Guzzle utilizando o Zend Http
     * 
     * @param string $method
     * @param string $url
     * @param [type] $body
     * @return $this
     */
    public function request(string $method, string $url, $body = null)
    {
        if(is_null($body)){
            $body = [];
        }
        $method = strtoupper($method);
        if(!is_array($body)){
            return false;
        }
        if(!in_array($method, ['GET', 'POST', 'PUT', 'PATH', 'DELETE'])){
            return false;
        }
        if(isset($body['json'])){
            $body = $body['json'];
        }
        $this->request = \Zend\Http\ClientStatic::$method(
            $this->guzzle['base_uri'] . $url,
            $body,
            [
                'Accept' => 'application/json',
                'Authorization' => $this->guzzle['headers']['Authorization']
            ]
        );
        return $this;
    }

    /**
     * getBody function
     *
     * Existe para garantir a continuidade e fluidez das chamadas
     * 
     * @return void
     */
    public function getBody()
    {
        return $this;
    }

    /**
     * getContents function
     *
     * Pega todos os dados fluidos realiza as verificações bases,
     * como se a zoop retornou algum error, se sim cria a exception
     * 
     * @return string
     * @throws Exception
     */
    public function getContents()
    {
        $properties = get_object_vars($this);
        $request = $properties['request'];
        $arrayResponse  = \json_decode($request->getContent(), true);
        if(isset($arrayResponse['error'])){
            throw new \Exception(\json_encode($arrayResponse), 1);
        }
        return \json_encode($arrayResponse);
    }
}