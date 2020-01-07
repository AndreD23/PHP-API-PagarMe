<?php

require __DIR__ . "/vendor/autoload.php";

$client = (new \Source\Models\User())->findById(1);
$pagarme = new \PagarMe\Client(PAGARME_API_KEY);

$newCard = true;

if($newCard){
    $getCreditCard = $pagarme->cards()->create([
        "holder_name" => "ANDRÉ DORNELES PEREIRA",
        "number" => "5440595842436848",
        "expiration_date" => "0620",
        "cvv" => "998"
    ]);

    

}

