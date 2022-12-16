<?php

namespace App\Paypal;

use Framework\Config\Config;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Payments\AuthorizationsGetRequest;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;
use Framework\Exception\PaymentAmountMissmatchException;
use Framework\Request\Request;

class PaypalPayment
{
    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly bool $sandbox
    ) {
    }

    public function ui(object $cart): string
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $paypalId =
            'https://www.paypal.com/sdk/js?client-id=' .
            Config::get('PAYPAL_CLIENT_ID') .
            '&currency=EUR&intent=authorize';
        $subTotal = (int) ($cart->getOrderSubTotal() * 100);
        $shippingCost = (int) ($cart->getShippingCost() * 100);
        $total = $subTotal + $shippingCost;
        $order = json_encode([
            'purchase_units' => [
                [
                    'description' => 'Liste des achats',
                    'items' => array_map(function ($cart) {
                        return [
                            'name' => $cart->getManga()->getTitle(),
                            'quantity' => $cart->getQuantity(),
                            'unit_amount' => [
                                'value' => $cart->getManga()->getPrice(), // Mes sommes sont en centimes d'euros
                                'currency_code' => 'EUR',
                            ],
                        ];
                    }, $cart->getOrderQuantities()),
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => $total / 100,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'EUR',
                                'value' => $subTotal / 100,
                            ],
                            'shipping' => [
                                'currency_code' => 'EUR',
                                'value' => $shippingCost / 100,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $_SESSION['cart'] = $cart;
        $cart = json_encode($cart);
        return <<<HTML
<script src='{$paypalId}'></script>
<!-- Set up a container element for the button -->

<div id="paypal-button-container"></div>

<script>
  paypal.Buttons({
    // Sets up the transaction when a payment button is clicked
    createOrder: (data, actions) => {
      return actions.order.create({$order});
    },
    // Finalize the transaction after payer approval
    onApprove: async (data, actions) => {
      const authorization = await actions.order.authorize()
      const authorizationId = authorization.purchase_units[0].payments.authorizations[0].id
      await fetch('/paypal', {
        method: 'post',
        headers: {
          'content-type': 'application/json'
        },
        body: 
        JSON.stringify({authorizationId})
      })
      alert('Votre paiement a bien été enregistré')
    }
  }).render('#paypal-button-container');
</script>
HTML;
    }

    public function handle(object $order): void
    {
        $total = $order->getSubtotall() + $order->getShippingCost();
        $request = Request::fromGlobals();
        if ($this->sandbox) {
            $environment = new SandboxEnvironment(
                $this->clientId,
                $this->clientSecret
            );
        } else {
            $environment = new ProductionEnvironment(
                $this->clientId,
                $this->clientSecret
            );
        }
        $client = new PayPalHttpClient($environment);
        $authorizationId = $request->getParsedBody()['authorizationId'];
        $order = $request->getParsedBody()['order'];
        $request = new AuthorizationsGetRequest($authorizationId);
        $authorizationResponse = $client->execute($request);
        $amount = $authorizationResponse->result->amount->value;
        if ($amount !== $total) {
            throw new PaymentAmountMissmatchException($amount, $total);
        }

        // On peut récupérer l'Order créé par le bouton
        $orderId =
            $authorizationResponse->result->supplementary_data->related_ids
                ->order_id;
        // $request = new OrdersGetRequest($orderId);
        // $orderResponse = $client->execute($request);

        // Vérifier si le stock est dispo

        // Verrouiller le produit (retirer du stock pour éviter une commande en parallèle entre temps)

        // Sauvegarder les informations de l'utilisateur

        // On capture l'autorisation
        $request = new AuthorizationsCaptureRequest($authorizationId);
        $response = $client->execute($request);
        if ($response->result->status !== 'COMPLETED') {
            throw new \Exception();
        }
    }
}
