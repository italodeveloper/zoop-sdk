<?php
namespace Zoop\Core;

class ZendAdapter 
{
    protected $guzzle;
    public function __construct(array $guzzle)
    {
        $this->guzzle = $guzzle;
    }  
    
    public function request(string $method, string $url, $body = null)
    {
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

    public function getBody()
    {
        return $this;
    }

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