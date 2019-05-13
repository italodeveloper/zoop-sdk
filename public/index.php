<?php
use Zoop\Core\Config;
use Zoop\ZoopClient;

require __DIR__ . '/../vendor/autoload.php';

$token = 'zpk_test_xjT8sQwPhZ8ivZfbuN5uiLcP';
$marketplace = 'd0024d3f01ea49f09814d282d38e3b3f';
$vendedor = '38e0c71e9c7c465080b7c049ae85bcb3';

$client = new ZoopClient(
    Config::configure($token, $marketplace, $vendedor)
);

$client->getBuyer("3524543");


exit;
$userCreated = $client->createBuyer(array(
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
        'country_code' => 'BR',
    ),
));

$ticker = $client->generateTicket(
    [
        'amount' => 42.99,
        'description' => 'Pagamento Zoop', //ADM
        'top_instructions' => 'Instruções de pagamento',
        'body_instructions' => 'Não receber após a data de vencimento.',
        'expiration_date' => (string)date('Y-m-d'),
        'payment_limit_date' => (string)date('Y-m-d')
    ],
    $userCreated['id']
);


print_r($ticker);

/*
#Pagamento com cartão de credito Avulso
$pagamento = $client->payCreditCard(
    [
        'description' => 'Plano nitro',
        'amount' => 49.99,
        'card' => [
            'card_number' => '5201561050024014',
            'holder_name' => 'João Silva',
            'expiration_month' => '03',
            'expiration_year' => '2018',
            'security_code' => '123',
        ]
    ]
);
*/