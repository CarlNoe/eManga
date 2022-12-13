<?php

namespace js;
use Framework\HttpMethode\Cookie;

require_once __DIR__ . '/../../vendor/autoload.php';

$co = Cookie::getInstance();

$content = trim(file_get_contents('php://input'));
$data = json_decode($content, true);

//get product id

$id = 'id' . $data['idManga'];
$quantity = $data['quantity'];
$co->has('cart') ? ($cart = json_decode($co->get('cart'), true)) : ($cart = []);
//check if product already in cart

if (array_key_exists($id, $cart)) {
    $cart[$id] += $quantity;
} else {
    $cart[$id] = $quantity;
}

$co->set('cart', $cart, time() + 3600);

$response = [
    'InCart' => true,
];
echo json_encode($response);
