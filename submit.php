<?php
    $postData = json_decode(file_get_contents('php://input'));

    $payload = json_encode($postData->elements);
    $url = 'https://' . $postData->subdomain . '.recras.nl/api2.php/contactformulieren/' . $postData->formID . '/opslaan';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    header('Content-type: application/json');
    echo $result;
