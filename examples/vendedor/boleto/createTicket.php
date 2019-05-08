<?php
use Zoop\BasicAuth;
use Zoop\Pay;

require __DIR__ . '/../../../vendor/autoload.php';

$marketplace = 'd0024d3f01ea49f09814d282d38e3b3f';
$token = 'zpk_test_xjT8sQwPhZ8ivZfbuN5uiLcP';
$vendedor = '38e0c71e9c7c465080b7c049ae85bcb3';

/** @var $idUser */
$idUser = '';

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
], $idUser);

echo json_encode($pagamento);