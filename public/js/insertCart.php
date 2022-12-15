<?php

namespace js;

use Framework\HttpMethode\Cookie;

require_once __DIR__ . '/../../vendor/autoload.php';

$cookie = Cookie::getInstance();

$content = trim(file_get_contents('php://input'));
$data = json_decode($content, true);

//get product id

$id = 'id' . $data['idManga'];
$quantity = $data['quantity'];
$cookie->has('cart')
    ? ($cart = json_decode($cookie->get('cart'), true))
    : ($cart = []);
//check if product already in cart

if (array_key_exists($id, $cart)) {
    $cart[$id] += $quantity;
} else {
    $cart[$id] = $quantity;
}

$cookie->set('cart', $cart, 1800);

$response = [
    'InCart' => true,
];
echo json_encode($response);
