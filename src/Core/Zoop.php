<?php
namespace Zoop;

/**
 * abstract Zoop class
 * 
 * Essa classe abstrata é responsavel pela magica do
 * pacopte de pagamentos ela abstrai todos os metodos das
 * classes registradas na função getBundles, e pertime
 * a qualquer classe que a extenda utilizar qualquer função
 * dos bundles registados, podendo assim se criar a classe
 * Zoop\ZoopClient que realiza todas as operações do pacote.
 * 
 * @package Zoop
 * @author italodeveloper <italoaraujo788@gmail.com>
 * @version 1.0.0
 */
abstract class Zoop
{
    /** @var $configurations */
    public $configurations;
    /** @var $namespace */
    private $namespace;

    public function __construct(array $configurations)
    {
        $this->configurations = $configurations;
        $this->namespace = __NAMESPACE__ . '\\';
    }

    /**
     * function getBundles
     * 
     * Registra todas as classes da biblioteca
     * em um array para serem listados para utilização
     *
     * @return array
     */
    private function getBundles()
    {
        return [
            WebHook\WebHook::class,
            Marketplace\Transactions::class,
            MarketPlace\Buyers::class,
            Payment\CreditCard::class,
            Payment\Ticket::class
        ];
    }

    /**
     * getBundle function
     *
     * Pega o noem do Bundle dono da função
     * que foi requisitada
     * 
     * @param array $bundles
     * @param string $function
     * @return string|bool
     */
    private function getBundle(array $bundles, $function)
    {
        unset($bundles['allMethods']);
        for ($contador = 0; $contador < count($bundles); $contador++) { 
            $name = array_keys($bundles[$contador])[0];
            $targetFunction = $bundles[$contador][$name][0];
            if(\in_array($function, $targetFunction)){
                return $name;
            }
        }
        return false;
    }

    /**
     * getAllBundle function
     *
     * Lista todos os metodos que estão dentro dos Bundles
     * registados e lista todos os bundles também para serem
     * reutilizados.
     * 
     * @return array
     */
    private function getAllBundle()
    {
        $bundleCollection = ['allMethods' => []];
        foreach($this->getBundles() as $bundle)
        {
            foreach (\get_class_methods($bundle) as $method) {
                array_push($bundleCollection['allMethods'], $method);
            }
            $bundleCollection[] = [$bundle => [\get_class_methods($bundle)]];
        }
        return $bundleCollection;
    }

    /**
     * hookBundle function
     *
     * @param string $class
     * @param string $method
     * @param $params
     * @return mixed|bool
     */
    private function hookBundle($class, $method, $params)
    {
        $metodos = \get_class_methods($class);
        if(in_array($method, $metodos)){
            return call_user_func_array(array(new $class($this->configurations), $method), $params);
        }
        return false;
    }

    /**
     * gunction __call
     * 
     * Pega metodos que estão fora dessa classe
     * porem que pertencem ao namespace e estão
     * registados no registro de bundles acima
     * e utiliza de funções especiais para habiliar
     * esses metodos fora dessas classes
     *
     * @param [type] $name
     * @param [type] $arguments
     * @return bool|mixed
     */
    public function __call($name, $arguments)
    {
        $bundles = $this->getAllBundle();
        if(!in_array($name, $bundles['allMethods'])){
            return false;
        }
        $bundle = $this->getBundle($bundles, $name);
        if(!$bundle){
            return false;
        }
        return $this->hookBundle($bundle, $name, $arguments);
    }
}