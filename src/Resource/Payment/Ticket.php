<?php
namespace Zoop\Payment;

use Zoop\Marketplace\Transactions;

class Ticket 
{
    /** @var $configuration  */
    protected $configuration;
    protected $transactions;
    public function __construct(array $configuration)
    {
        $this->transactions = new Transactions($configuration);
        $this->configuration = $configuration;
    }

    /**
     * prepareTicket function
     *
     * Prepara o boleto e preenche o mesmo
     * com dados fixos utilizados na request para geração
     * de boletos.
     * 
     * @param array $ticket
     * @param string $userId
     * @return array
     */
    private function prepareTicket(array $ticket, string $userId)
    {
        return [
            'amount' => ($ticket['amount'] * 100),
            'currency' => 'BRL',
            'description' => $ticket['description'],
            'payment_type' => 'boleto',
            'payment_method' => [
                'top_instructions' => $ticket['top_instructions'],
                'body_instructions' => $ticket['body_instructions'],
                'expiration_date' => $ticket['expiration_date'],
                'payment_limit_date' => $ticket['payment_limit_date'],
            ],
            'capture' => false,
            'on_behalf_of' => $this->configuration['auth']['on_behalf_of'],
            'source' => [
                'usage' => 'single_use',
                'type' => 'customer',
                'capture' => false,
                'on_behalf_of' => $this->configuration['auth']['on_behalf_of']
            ],
            'customer' => $userId,
        ];
    }

    /**
     * processTicket function
     *
     * Processa o boleto na Zoop, e retorna somente os dados
     * necessarios para pegar o boleto e mostrar dados de valor.
     * 
     * @param array $ticket
     * @param string $userId
     * @return array|bool
     */
    private function processTicket(array $ticket, string $userId)
    {
        $ticket = $this->prepareTicket($ticket, $userId);
        $request = $this->configuration['guzzle']->request(
            'POST', '/v1/marketplaces/'. $this->configuration['marketplace']. '/transactions', 
            ['json' => $ticket]
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return [
                'id' => $response['id'],
                'ticketId' => $response['payment_method']['id'],
                'status' => $response['status'],
            ];
        }
        return false;
    }

    /**
     * generateTicket function
     *
     * Gera o boleto e retorna os dados principais
     * do mesmo, como codigo de barras, url para download
     * no s3 e mais.
     * 
     * @param array $ticket
     * @param string $userId
     * @return array|bool
     */
    public function generateTicket(array $ticket, string $userId)
    {
        $generatedTicket = $this->processTicket($ticket, $userId);
        $request = $this->configuration['guzzle']->request(
            'GET', '/v1/marketplaces/'. $this->configuration['marketplace']. '/boletos/' . $generatedTicket['ticketId']
        );
        $response = \json_decode($request->getBody()->getContents(), true);
        if($response && is_array($response)){
            return [
                'id' => $generatedTicket['id'],
                'ticketId' => $generatedTicket['ticketId'],
                'url' => $response['url'],
                'barcode' => $response['barcode'],
                'status' => $generatedTicket['status']
            ];
        }
        return false;
    }

    /**  
     * getTicket function
     *
     * Pega os detaçhes de uma 
     * 
     * @param string $transaction
     * @return array|bool
     */
    public function getTicket(string $transaction)
    {
        return $this->transactions->getTransaction($transaction);
    }
}