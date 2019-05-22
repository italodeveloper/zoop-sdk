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
     * @param null|string $referenceId
     * @return array
     */
    private function prepareCreditCard(array $card, $referenceId = null)
    {
        $payment = array(
            'amount' => ($card['amount'] * 100),
            'currency' => 'BRL',
            'description' => $card['description'],
            'on_behalf_of' => $this->configurations['auth']['on_behalf_of'],
            'statement_descriptor' => 'SEMINOVOS BH',
            'payment_type' => 'credit',
            'source' => array(
                'usage' => 'single_use',
                'amount' => ($card['amount'] * 100),
                'currency' => 'BRL',
                'type' => 'card',
                'card' => array(
                    'card_number' => $card['card']['card_number'],
                    'holder_name' => $card['card']['holder_name'],
                    'expiration_month' => $card['card']['expiration_month'],
                    'expiration_year' => $card['card']['expiration_year'],
                    'security_code' => $card['card']['security_code'],
                ),
            ),
        );
        if(isset($card['installment_plan']) && is_array($card['installment_plan']) && $this->validateInstallment($card['installment_plan'])){
            $payment['installment_plan'] = $card['installment_plan'];
        }

        if(!is_null($referenceId)){
            $payment['reference_id'] = $referenceId;
        }
        return $payment;
    }

    /**
     * validateInstallment function
     *
     * Valida o parcelamento por cartão de credito.
     * 
     * @param array $installment
     * @return bool
     */
    private function validateInstallment(array $installment)
    {
        if(!isset($installment['mode'])
        || !isset($installment['number_installments'])
        || empty($installment['mode'])
        || empty($installment['number_installments'])){
            return false;
        }
        if($installment['mode'] == 'with_interest' || $installment['mode'] == 'interest_free'){
            return true;
        }
        return false;
    }

    /**
     *
     * payCreditCard function
     *
     * Gera o pagamento com cartão de credito,
     * realizando com simplicidade a operação.
     *
     * @param array $card
     * @param null|string $referenceId
     *
     * @return array|bool|void
     * @throws \Exception
     */
    public function payCreditCard(array $card, $referenceId = null)
    {
        /**
         * Adiciona o pacerlamento ao cartão de credito mantendo a integridade com o funcionamento atual.
         */
        try {
            $payment = $this->prepareCreditCard($card, $referenceId);
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