<?php
$url = "https://checkout-test.adyen.com/v67/paymentLinks";

$payments_data = $_POST;

//echo $payments_data;

$additional_data = [
    'reference' => $_POST['REF'].date("Ymt").'/'.time(),
    'merchantAccount' => 'KenjiW',
    'amount' => [
        'value' => $_POST['amount'],
        'currency' => 'JPY'
    ],

    'installmentOptions' =>
        [
            'card' =>[
                    'plans' => ['regular','revolving'],
                    'values' => [2,3,5,10]
                ],
        ],
    //'returnUrl' => 'http://127.0.0.1:8080/return.php',
    //'channel' => 'Web',
    'additionalData' => [
        'allow3DS2' => 'true'
    ],
    //'origin' => 'http://127.0.0.1:8080',
    'countryCode' => 'JP',
    //to enable Torkanization for upcoming payment
    'storePaymentMethod'=> true,
    'shopperLocale'=> 'ja-JP',
    'recurringProcessingModel'=> 'CardOnFile',
    'description' => 'Black Sneakers - X51',
    'shopperReference'=> $_POST['SRN'],
    'description' => $_POST['DESC']
];

unset($payments_data['REF']);
unset($payments_data['SRN']);
unset($payments_data['DESC']);
unset($payments_data['send']);
unset($payments_data['amount']);

$final_payment_data = array_merge($payments_data, $additional_data);

$curl_http_header = array(
    "X-API-Key: QEyhmfxL4PJahZCw0m/n3Q5qf3VaY9UCJ1+XWZe9W27jmlZiv4PD4jhfNMofnLr2K5i8/0QwV1bDb7kfNy1WIxIIkxgBw==-lUKXT9IQ5GZ6d6RH4nnuOG4Bu//eJZxvoAOknIIddv4=-<anpTLkW{]ZgGy,7",
    //↑ここにAPIキーを貼り付けてください。例："X-API-Key: hogehogehogehoge_my_api_key",
    "Content-Type: application/json"
);

$curl = curl_init();

curl_setopt_array(
    $curl,
    [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => json_encode($final_payment_data),
        CURLOPT_HTTPHEADER     => $curl_http_header,
        CURLOPT_VERBOSE        => true
    ]
);

$payments_response = curl_exec($curl);
$file = 'PBL_CallResponse.txt';
$current = $payments_response;
file_put_contents($file, $current);
$arr = json_decode($payments_response,true);



header('Content-Type: application/json');
echo $arr['url'];
//echo $payments_response;
$pipe = popen("clip", "w");
fwrite($pipe, $arr['url']);
pclose($pipe);

curl_close($curl);
?>
