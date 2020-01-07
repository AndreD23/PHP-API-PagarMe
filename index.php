<?php

require __DIR__ . "/vendor/autoload.php";


/**
 * Exemplo utilizando biblioteca da PAGARME
 */
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

$newTransaction = false;

if($newTransaction){
    $creditCard = (new \Source\Models\CreditCard())->findById(1);
    $transaction = $pagarme->transactions()->create([
        "payment_type" => "credit_card",
        "amount" => (55.80 * 100),
        "card_id" => $creditCard->hash,
        "metadata" => [
            "order_id" => 1555
        ]
    ]);
}


/**
 * Exemplo com criação de um pacote do zero
 */

$pay = new \Source\Support\Payment();

// Cadastra o cartão
$pay->createCard(
    "ANDRE DORNELES PEREIRA",
    "5440595842436848",
    "0620",
    "998"
);

var_dump($pay->getResult());

if(!$pay->getResult()->valid){
    echo "<h3>Cartão inválido</h3>";
    die();
}

// Realiza o pagamento
$pay->withCard(
    1250,
    (new \Source\Models\CreditCard())->findById(1),
    1230.34,
    2
);

if(!$pay->getResult()->paid){
    echo "<h3>Pagamento não efetuado</h3>";
    die();
}

