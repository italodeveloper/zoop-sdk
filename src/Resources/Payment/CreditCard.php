<?php
namespace Zoop\Payment;
use Zoop\Zoop;

/**
 * CreditCard class
 * 
 * Essa classe é responsavel por realizar o pagamento
 * utilizando o cartão de credito, preparando e hidratando
 * o mesmo com os dados estaticos.
 * 
 * @method Zoop\Payment\CreditCard payCreditCard(array $card)
 * 
 * @package Zoop\Payment
 * @author italodeveloper <italoaraujo788@gmail.com>
 * @version 1.0.0
 */
class CreditCard extends Zoop 
{
    public function __construct($configurations)
    {
        parent::__construct($configurations);
    }

    /**
     * prepareCreditCard function
     *
     * Hidrata o array basico do cartão de credito
     * adicionando dados imutaveis para realizar a operação.
     *
     * @param array $card
     * @return array
     */
    private function prepareCreditCard(array $card)
    {
        return [
            'amount' => ($card['amount'] * 100),
            'currency' => 'BRL',
            'description' => $card['description'],
            'on_behalf_of' => $this->configurations['auth']['on_behalf_of'],
            'statement_descriptor' => 'SEMINOVOS BH',
            'payment_type' => 'credit',
            'source' => [
                'usage' => 'single_use',
                'amount' => ($card['amount'] * 100),
                'currency' => 'BRL',
                'type' => 'card',
                'card' => [
                    'card_number' => $card['card']['card_number'],
                    'holder_name' => $card['card']['holder_name'],
                    'expiration_month' => $card['card']['expiration_month'],
                    'expiration_year' => $card['card']['expiration_year'],
                    'security_code' => $card['card']['security_code'],
                ],
            ],
        ];
    }

    /**
     *
     * payCreditCard function
     *
     * Gera o pagamento com cartão de credito,
     * realizando com simplicidade a operação.
     *
     * @param array $card
     * @return array|bool
     */
    public function payCreditCard(array $card)
    {
        try {
            $payment = $this->prepareCreditCard($card);
            $request = $this->configurations['guzzle']->request(
                'POST', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transactions',
                ['json' => $payment]
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return $response;
            }
            return false;
       } catch (\Exception $e){        
            return $this->ResponseException($e);
       }
    }
}