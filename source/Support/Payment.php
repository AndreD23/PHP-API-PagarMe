<?php

namespace Source\Support;

class Payment
{

    private $apiUrl;
    private $apiKey;
    private $endpoint;
    private $build;
    private $result;

    public function __construct()
    {
        $this->apiUrl = "https://api.pagar.me/1";
        $this->apiKey = PAGARME_API_KEY;
    }

    public function createCard(string $holder_name, string $card_number, string $expiration_date, int $cvv): Payment
    {
        $this->endpoint = "/cards";
        $this->build = [
            "holder_name" => $holder_name,
            "number" => $card_number,
            "expiration_date" => $expiration_date,
            "cvv" => $cvv
        ];

        $this->post();
        return $this;
    }

    public function withCard(int $order_id, CreditCard $card, string $amount, int $installments): Payment
    {
        $this->endpoint = "/transactions";
        $this->build = [
            "payment_type" => "credit_card",
            "amount" => ($amount * 100),
            "installments" => $installments,
            "card_id" => $card->hash,
            "metadata" => [
                "order_id" => $order_id
            ]
        ];
    
        $this->post();
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    private function post()
    {
        $url = $this->apiUrl . $this->endpoint;
        $api = ["api_key" => $this->apiKey];
        $ch = curl_unit($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array_merge($this->build, $api)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);
        $this->result = json_decode(curl_exec($ch));
        curl_close($ch);
    }
}