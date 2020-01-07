<?php

require __DIR__ . "/vendor/autoload.php";

$client = (new \Source\Models\User())->findById(1);
$pagarme = new \PagarMe\Client(PAGARME_API_KEY);

$newCard = false;

if($newCard){
    $getCreditCard = $pagarme->cards()->create([
        "holder_name" => "ANDRE DORNELES PEREIRA",
        "number" => "5440595842436848",
        "expiration_date" => "0620",
        "cvv" => "998"
    ]);

    if(!$getCreditCard->valid){
        echo "<h3>Cartão inválido</h3>";
        die();
    }

    $createCreditCard = new \Source\Models\CreditCard();
    $createCreditCard->user = $client->id;
    $createCreditCard->hash = $getCreditCard->id;
    $createCreditCard->brand = $getCreditCard->brand;
    $createCreditCard->last_digits = $getCreditCard->last_digits;
    $createCreditCard->save();
}

$newTransaction = true;

if($newTransaction){
    $creditCard = (new \Source\Models\CreditCard())->findById(1);
    $transaction = $pagarme->transactions()->create([
        "amount" => (55.80 * 100),
        "card_id" => $creditCard->hash,
        "metadata" => [
            "order_id" => 1555
        ]
    ]);
}