<?php
use Zoop\AuthHelper;
namespace Zoop;
class Pay 
{
    protected $configuration;
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    private function prepareTicket(array $ticket, string $userId)
    {
        return array(
            'amount' => ($ticket['amount'] * 100),
            'currency' => 'BRL',
            'description' => $ticket['description'],
            'payment_type' => 'boleto',
            'payment_method' => array(
                'top_instructions' => $ticket['top_instructions'],
                'body_instructions' => $ticket['body_instructions'],
                'expiration_date' => $ticket['expiration_date'],
                'payment_limit_date' => $ticket['payment_limit_date'],
            ),
            'capture' => false,
            'on_behalf_of' => $this->configuration['auth']['on_behalf_of'],
            'source' => array(
                'usage' => 'single_use',
                'type' => 'customer',
                'capture' => false,
                'on_behalf_of' => $this->configuration['auth']['on_behalf_of']
            ),
            'customer' => $userId,
        );
    }

    private function processTicket(array $ticket, string $userId)
    {
        $ticket = $this->prepareTicket($ticket, $userId);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration['base_url'] . '/v1/marketplaces/'. $this->configuration['marketplace'] . '/transactions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \json_encode($ticket),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic ' . AuthHelper::getBasic($this->configuration),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Content-Type: application/json',
                'Host: api.zoop.ws',
                'accept-encoding: gzip, deflate',
                'cache-control: no-cache',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            $response = \json_decode($response, true);
            return [
                'id' => $response['id'],
                'ticketId' => $response['payment_method']['id'],
                'status' => $response['status'],
            ];
        }
    }

    public function ticket(array $ticket, string $userId)
    {
        $generatedTicket = $this->processTicket($ticket, $userId);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration['base_url'] . '/v1/marketplaces/'. $this->configuration['marketplace'] .'/boletos/' . $generatedTicket['ticketId'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic ' . AuthHelper::getBasic($this->configuration),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Host: api.zoop.ws',
                'accept-encoding: gzip, deflate',
                'cache-control: no-cache',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            $ticket = \json_decode($response, true);
            return [
                'id' => $generatedTicket['id'],
                'ticketId' => $generatedTicket['ticketId'],
                'url' => $ticket['url'],
                'barcode' => $ticket['barcode'],
                'status' => $generatedTicket['status']
            ];
        }
    }   

    public function debitCard(array $card)
    {
        $payment = json_encode(array(
            'amount' => ($card['amount'] * 100),
            'currency' => 'BRL',
            'description' => $card['description'],
            'on_behalf_of' => $this->configuration['auth']['on_behalf_of'],
            'statement_descriptor' => 'SEMINOVOS BH',
            'payment_type' => 'debit',
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
        ));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration['base_url'] . '/v1/marketplaces/'. $this->configuration['marketplace'] . '/transactions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payment,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic ' . AuthHelper::getBasic($this->configuration),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Content-Type: application/json',
                'Host: api.zoop.ws',
                'accept-encoding: gzip, deflate',
                'cache-control: no-cache',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return \json_decode($response, true);
        }
    }

    public function creditCard(array $card)
    {
        $payment = json_encode(array(
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
        ));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration['base_url'] . '/v1/marketplaces/'. $this->configuration['marketplace'] . '/transactions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payment,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic ' . AuthHelper::getBasic($this->configuration),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Content-Type: application/json',
                'Host: api.zoop.ws',
                'accept-encoding: gzip, deflate',
                'cache-control: no-cache',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return \json_decode($response, true);
        }
    }

    public function get(string $transactionId)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration['base_url'] . '/v1/marketplaces/'. $this->configuration['marketplace'] .'/transactions' . $transactionId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Basic ' . AuthHelper::getBasic($this->configuration),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Host: api.zoop.ws',
                'accept-encoding: gzip, deflate',
                'cache-control: no-cache'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return json_decode($response, true);
        }
    }
}