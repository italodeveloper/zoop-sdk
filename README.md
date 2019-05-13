# Zoop SDK
SDK Não oficial Zoop PHP, para realizar integração com o gateway de pagamento.

Observação a contribuidores: só será mergido bundles com o mesmo padrão de projeto, não repita nomes de funções dentro dos novos bundles para não confitar com os atuais.

## Criando o cliente para realizar as operações da Zoop
``` php
<?php
use Zoop\Core\Config;
use Zoop\ZoopClient;

require __DIR__ . '/../vendor/autoload.php';

$token = 'zpk_test_xjT8sQwPhZ8ivZfbuN5uiLcP'; /** Token gerado ADM Mkt Zoop */
$marketplace = 'd0024d3f01ea49f09814d282d38e3b3f'; /* ID do Marketplace **/
$vendedor = '38e0c71e9c7c465080b7c049ae85bcb3'; /** ID do vendedor do marketplace */

$client = new ZoopClient(
    Config::configure($token, $marketplace, $vendedor)
);
```
O cliente de integração pode utilizar a seguinte combinação de metodos
que já estão prontos e abstraidos para simples utilização.  

``` php
/**
 * Explicação do Array de usuarios Zoop
 * 
 * first_name => Nome completo
 * taxpayer_id => ID na sua aplicação local
 * email => E-mail (Cuidado a Zoop não controla corretamente os cadastros)
 * line1 => Endereço 1 & 2 São (Rua e número da casa + apto/bloco)
 * neighborhood => Bairro
 * city => Cidade
 * state => Estado (Sigla)
 * postal_code => Cep
 * country_code => Pais (Sigla)
 * 
 * array(
 *      'first_name' => 'Ricardo Pedrosa',
 *      'taxpayer_id' => '11836128770',
 *      'email' => 'ricardo.pedrosa@zoop.co',
 *      'address' => array(
 *          'line1' => 'Av Americas, 500',
 *          'line2' => 'Citta América',
 *          'neighborhood' => 'Barra da Tijuca',
 *          'city' => 'Rio de Janeiro',
 *          'state' => 'RJ',
 *          'postal_code' => '22845046',
 *          'country_code' => 'BR'
 *      ),
 *   );
 */
$criarUsuario = $client->createBuyer(array(
    'first_name' => 'Ricardo Pedrosa',
    'taxpayer_id' => '11836128770',
    'email' => 'ricardo.pedrosa@zoop.co',
    'address' => array(
        'line1' => 'Av Americas, 500',
        'line2' => 'Citta América',
        'neighborhood' => 'Barra da Tijuca',
        'city' => 'Rio de Janeiro',
        'state' => 'RJ',
        'postal_code' => '22845046',
        'country_code' => 'BR'
    ),
));

$listarUsuarios = $client->getAllBuyers();
/** ID do usuario que foi retornado ao criar o mesmo */
$detalhesUsuario = $client->getBuyer('3524543');

$listarTransacoes = $client->getAllTransactions();
/** ID da transação que foi criada ao efetuar a mesma */
$detalhesTransacao = $client->getTransaction('543856475');

/**
 * Explicação do array de pagamento com cartão de credito
 * 
 * description => Descrição do pagamento no painel ADM Zoop
 * amount => Valor do pagamento
 * card_number => Numero do cartão de credito
 * holder_name => Nome no cartão de credito
 * expiration_month => mes de expiração do cartão
 * expiration_year => ano de expiração do cartão
 * security_code => codigo de segurança (verso) do cartão
 * 
 * array(
 *      'description' => 'Plano nitro',
 *      'amount' => 49.99,
 *      'card' => array(
 *          'card_number' => '5201561050024014',
 *          'holder_name' => 'João Silva',
 *          'expiration_month' => '03',
 *          'expiration_year' => '2018',
 *          'security_code' => '123',
 *      )
 *   );
 */

$detalhesPagamentoCartao = $client->payCreditCard(array(
    'description' => 'Plano nitro',
    'amount' => 49.99,
    'card' => array(
        'card_number' => '5201561050024014',
        'holder_name' => 'João Silva',
        'expiration_month' => '03',
        'expiration_year' => '2018',
        'security_code' => '123',
    )
));

/**
 * Explicação do array de geração e boletos
 * 
 * amount => Valor do pagamento
 * description => Descrição do pagamento no painel ADM Zoop
 * top_instructions => Instruçoes de pagamento em H1 no topo (MEIO) do boleto
 * body_instructions => Uma especie de paragrado sobre o nome das instruções acima
 * expiration_date => Data de expiração do boleto
 * payment_limit_date => Data que o boleto é aceito ser pago mesmo após o vencimento.
 * 
 * array(
 *      'amount' => 42.99,
 *      'description' => 'Pagamento Zoop',
 *      'top_instructions' => 'Instruções de pagamento',
 *      'body_instructions' => 'Não receber após a data de vencimento.',
 *      'expiration_date' => (string)date('Y-m-d'),
 *      'payment_limit_date' => (string)date('Y-m-d')
 *  );
 * 
 * Em segida é passado como string o ID do usuario que gerou o boleto.
 * $client->generateTicket(array(..), '3524543');
 */

$detalhesPagamentoBoleto = $client->generateTicket(array(
    'amount' => 42.99,
    'description' => 'Pagamento Zoop',
    'top_instructions' => 'Instruções de pagamento',
    'body_instructions' => 'Não receber após a data de vencimento.',
    'expiration_date' => (string)date('Y-m-d'),
    'payment_limit_date' => (string)date('Y-m-d')
),  '3524543');
```