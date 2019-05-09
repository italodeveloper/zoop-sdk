<?php
use Zoop\BasicAuth;
use Zoop\Pay;
use Zoop\User;
use Zoop\Transactions;
use Zoop\Marketplace\Buyers;

require __DIR__ . '/../vendor/autoload.php';

$marketplace = 'd0024d3f01ea49f09814d282d38e3b3f';
$token = 'zpk_test_xjT8sQwPhZ8ivZfbuN5uiLcP';
$vendedor = '38e0c71e9c7c465080b7c049ae85bcb3';


/*
#Boleto
$user = new User(
    BasicAuth::auth($token, $marketplace, $vendedor)
);

$userCreated = $user->createUser(array(
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

$payment = new Pay(
    BasicAuth::auth($token, $marketplace, $vendedor)
);

$pagamento = $payment->ticket([
    'amount' => 42.99,
    'description' => 'Pagamento Zoop', //ADM
    'top_instructions' => 'Instruções de pagamento',
    'body_instructions' => 'Não receber após a data de vencimento.',
    'expiration_date' => (string)date('Y-m-d'),
    'payment_limit_date' => (string)date('Y-m-d')
], $userCreated['id']);

echo json_encode($pagamento);
*/

/*
#DEBITO
$payment = new Pay(
    BasicAuth::auth($token, $marketplace, $vendedor)
);

$pagamento = $payment->debitCard(array(
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

echo json_encode($pagamento);
*/

/*
#CREDITO
$payment = new Pay(
    BasicAuth::auth($token, $marketplace, $vendedor)
);

$pagamento = $payment->creditCard(array(
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

echo json_encode($pagamento);
*/


/*
#Lista de transações
$transactions = new Transactions(
    BasicAuth::auth($token, $marketplace, $vendedor)
);
$allTransactions = $transactions->getAll();
$transaction = $transactions->get('05a37ed543a446278d7a32be5dab6386');
echo \json_encode($allTransactions);
*/