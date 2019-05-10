<?php
namespace Zoop\Payment;
use Zoop\Marketplace\Transactions;

class CreditCard extends Transactions
{
    /** @var $configuration  */
    protected $configuration;
    /** @var Transactions  */
    protected $transactions;

    public function __construct($configuration)
    {
        parent::__construct($configuration);
        //$this->transactions = new Transactions($configuration);
        $this->configuration = $configuration;
    }

    /**
     * prepare function
     *
     * Hidrata o array basico do cartão de credito
     * adicionando dados imutaveis para realizar a operação.
     *
     * @param array $card
     * @return array
     */
    private function prepare(array $card)
    {
        return array(
            'amount' => ($card['amount'] * 100),
            'currency' => 'BRL',
            'description' => $card['description'],
            'on_behalf_of' => $this->configuration['auth']['on_behalf_of'],
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
    }

    /**
     *
     * pay function
     *
     * Gera o pagamento com cartão de credito,
     * realizando com simplicidade a operação.
     *
     * @param array $card
     * @return array|bool
     */
    public function pay(array $card)
    {
        $payment = $this->prepare($card);
        $request = $this->configuration['guzzle']->request(
            'POST', '/v1/marketplaces/'. $this->configuration['marketplace']. '/transactions',
            ['json' => $payment]
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return $response;
        }
        return false;
    }
}