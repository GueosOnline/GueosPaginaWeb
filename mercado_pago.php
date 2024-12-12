<?php

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

require 'vendor/autoload.php';
MercadoPagoConfig::setAccessToken("TEST-5643175586443360-120516-eee2547feaf32253850f8c060f52cec7-192220680");

$client = new PreferenceClient();
$preference = $client->create([

    "items" => [
        [
            "id" => "DEP-0001",
            "title" => "My product",
            "quantity" => 1,
            "unit_price" => 20000
        ],
    ],

]);
/*
$preference->back_urls = array(
    "success" => "https://web.whatsapp.com/",
    "failure" => "http://localhost:8080/PaginaWeb/Fallo.php",
);

$preference->auto_return = "approved";*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>

<body>

    <div id="wallet_container"></div>

    <script>
        const mp = new MercadoPago("TEST-4027ccea-7466-4fb9-b495-351e0cd11ad8", {
            locale: 'es-CO'
        });

        mp.bricks().create("wallet", "wallet_container", {
            initialization: {
                preferenceId: '<?php echo $preference->id; ?>'
            },
        });
    </script>
</body>

</html>