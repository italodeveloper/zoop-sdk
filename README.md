![alt text](https://zoop.co/dist/imgs/zoop-logo3.png "Logo Zoop")

# Zoop SDK - PHP :elephant:
SDK Não oficial Zoop PHP, para realizar integração com o gateway de pagamento.

Observação a contribuidores: só será mergido bundles com o mesmo padrão de projeto, não repita nomes de funções dentro dos novos bundles para não confitar com os atuais.

instalando via composer
```
composer require italodeveloper/zoop-sdk
```

## Criando o cliente para realizar as operações da Zoop
``` php
<?php
use Zoop\Core\Config;
use Zoop\ZoopClient;

require __DIR__ . '/../vendor/autoload.php';

$token = 'zpk_test_Xxxxxx'; /** Token gerado ADM Mkt Zoop */
$marketplace = 'd0024d3f01ea4xxxxxxxxxx'; /* ID do Marketplace **/
$vendedor = '38e0c71e9c7c465080bxxxxxxxxx'; /** ID do vendedor do marketplace */

$client = new ZoopClient(
    Config::configure($token, $marketplace, $vendedor)
);
```
O cliente de integração pode utilizar a seguinte combinação de metodos
que já estão prontos e abstraidos para simples utilização.  

``` php
try {   
    $listarTransacoes = $client->getAllTransactions();
    print_r($listarTransacoes);
    #var_dump($listarTransacoes);
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
```
Para as demais funções, acesse nossa wiki e confira: 
[Zoop SDK Wiki](https://github.com/italodeveloper/zoop-sdk/wiki).

Desenvolvido com :heart: por [italodeveloper](https://www.linkedin.com/in/%C3%ADtalo-araujo/) e contribuidores.
