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

$selectedUser = $user->getUser('4b47a028ee2c4b88ad1291411caef77d');

print_r($selectedUser);