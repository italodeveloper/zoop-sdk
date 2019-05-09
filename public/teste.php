<?php
use Zoop\BasicAuth;
use Zoop\Marketplace\Buyers;
use Zoop\Payment\Ticket;

require __DIR__ . '/../vendor/autoload.php';

$marketplace = 'd0024d3f01ea49f09814d282d38e3b3f';
$token = 'zpk_test_xjT8sQwPhZ8ivZfbuN5uiLcP';
$vendedor = '38e0c71e9c7c465080b7c049ae85bcb3';

/*
$user = new Buyers(
    BasicAuth::auth($token, $marketplace, $vendedor)
);

$userCreated = $user->createBuyer(array(
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
*/
$payment = new Ticket(
    BasicAuth::auth($token, $marketplace, $vendedor)
);
print_r($payment->getTicket('afe2b49a22d643b88782337cdf4cefa8'));

exit;
$pagamento = $payment->generateTicket([
    'amount' => 42.99,
    'description' => 'Pagamento Zoop', //ADM
    'top_instructions' => 'Instruções de pagamento',
    'body_instructions' => 'Não receber após a data de vencimento.',
    'expiration_date' => (string)date('Y-m-d'),
    'payment_limit_date' => (string)date('Y-m-d')
], $userCreated['id']);

echo json_encode($pagamento);