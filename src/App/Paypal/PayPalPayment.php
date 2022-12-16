<?php

namespace App\Paypal;

use App\Entity\Manga;
use App\Entity\Order;
use App\Entity\OrderQuantity;
use Framework\Config\Config;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Payments\AuthorizationsGetRequest;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Framework\Exception\PaymentAmountMissmatchException;
use Framework\Request\Request;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use PayPalHttp\Serializer\Json;

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
                    }, $cart->getOrderQuantities()->toArray()),
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
    }).then(res => res.json()
    ).then(data => {
        if (data.success) {
            window.location.href = '/order/' + data.orderId
        } else {
            alert('Une erreur est survenue')
        }
    }
    )
    }
  }).render('#paypal-button-container');
</script>
HTML;
    }

    public function handle(object $order, string $authorizationId): void
    {
        $success = false;
        $total = $order->getOrderSubTotal() + $order->getShippingCost();
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
        $request = new AuthorizationsGetRequest($authorizationId);
        $authorizationResponse = $client->execute($request);
        $amount = $authorizationResponse->result->amount->value;
        var_dump($amount);
        var_dump($total);
        if ((float) $amount !== (float) $total) {
            throw new PaymentAmountMissmatchException($amount, $total);
        }

        // On peut récupérer l'Order créé par le bouton
        $orderId =
            $authorizationResponse->result->supplementary_data->related_ids
                ->order_id;
        $request = new OrdersGetRequest($orderId);

        // Vérifier si le stock est dispo pour chaque produit
        $this->checkStock($order);
        // Verrouiller le produit (retirer du stock pour éviter une commande en parallèle entre temps)
        $this->lockStock($order);
        //On enregistre la commande
        $this->saveOrder($order);
        //On enregistre les quantités de chaque produit
        $this->saveOrderQuantities($order->getOrderQuantities());

        // On capture l'autorisation
        $request = new AuthorizationsCaptureRequest($authorizationId);
        $response = $client->execute($request);
        if ($response->result->status !== 'COMPLETED') {
            throw new \Exception();
        }
        $success = true;
        // On redirige vers la page de confirmation
        echo json_encode(['success' => $success]);
    }

    private function checkStock(object $order): void
    {
        $mangaRepository = EntityManager::getRepository(Manga::class);

        foreach ($order->getOrderQuantities() as $orderQuantity) {
            var_dump($orderQuantity->getManga()->getId());
            $manga = $mangaRepository->find(
                $orderQuantity->getManga()->getId()
            );
            var_dump($manga);
            if ($orderQuantity->getQuantity() >= $manga->getStock()) {
                throw new \Exception();
            }
        }
    }

    private function lockStock(object $order): void
    {
        $mangaRepository = EntityManager::getRepository(Manga::class);
        foreach ($order->getOrderQuantities() as $orderQuantity) {
            $orderQuantity
                ->getManga()
                ->setStock(
                    $orderQuantity->getManga()->getStock() -
                        $orderQuantity->getQuantity()
                );
            $mangaRepository->updateManga(
                $orderQuantity->getManga(),
                $orderQuantity->getManga()->getId()
            );
        }
    }

    private function saveOrder(object $order): void
    {
        $orderRepository = EntityManager::getRepository(Order::class);
        $orderRepository->insertOrder($order);
    }

    private function saveOrderQuantities(object $orderQuantity): void
    {
        foreach ($orderQuantity as $orderQuantity) {
            $orderQuantityRepository = EntityManager::getRepository(
                OrderQuantity::class
            );
            $orderQuantityRepository->insertOrderQuantity($orderQuantity);
        }
    }
}
