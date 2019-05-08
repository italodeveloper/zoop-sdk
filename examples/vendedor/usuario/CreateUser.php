<?php
use Zoop\BasicAuth;
use Zoop\User;

require __DIR__ . '/../../../vendor/autoload.php';

$marketplace = 'd0024d3f01ea49f09814d282d38e3b3f';
$token = 'zpk_test_xjT8sQwPhZ8ivZfbuN5uiLcP';
$vendedor = '38e0c71e9c7c465080b7c049ae85bcb3';

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

print_r($userCreated);