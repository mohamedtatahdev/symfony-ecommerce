<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/commande/paiement/{id_order}', name: 'payment')]
    public function index($id_order, OrderRepository $orderRepository)
    {

      $order = $orderRepository->findOneBy([
        'id' => $id_order,
        'user' => $this->getUser()
    ]);
//verifie si la commande est bien à l'utilisateur
    if (!$order) {
        return $this->redirectToRoute('app_home');
    }


      $products_for_stripe = [];

      //recupere les produits
      foreach ($order->getOrderDetails() as $product) {
        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => number_format($product->getProductPriceWt() * 100, 0, '', ''),
                'product_data' => [
                    'name' => $product->getProductName(),
                    'images' => [
                      $_ENV['DOMAIN'].'/uploads/'.$product->getProductIllustration()
                    ]
                ]
            ],
            'quantity' => $product->getProductQuantity(),
        ];
    }

//recupere le transporteur
    $products_for_stripe[] = [
      'price_data' => [
          'currency' => 'eur',
          'unit_amount' => number_format($order->getCarrierPrice() * 100, 0, '', ''),
          'product_data' => [
              'name' => 'Transporteur : '.$order->getCarrierName(),
          ]
      ],
      'quantity' => 1,
  ];

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        
$checkout_session = Session::create([
  'customer_email' => $this->getUser()->getEmail(),
    'line_items' => [[
      $products_for_stripe
    ]],
    'mode' => 'payment',
    'success_url' => $_ENV['DOMAIN'] . '/success.html',
    'cancel_url' => $_ENV['DOMAIN'] . '/cancel.html',
  ]);

  return $this->redirect($checkout_session->url);
    }
} 