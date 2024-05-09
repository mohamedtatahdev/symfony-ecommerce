<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig',[
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt()
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository,Request $request): Response
    {
        $product = $productRepository->findOneById($id);
        $cart->add($product);
        $this->addFlash('success','Produit correctement ajouter à votre panier');


        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/decrease/{id}', name: 'cart_decrease')]
    public function decrease($id, Cart $cart, ): Response
    {
        $cart->decrease($id);
        $this->addFlash('success','Produit correctement supprimer de votre panier');


        return $this->redirectToRoute('cart');
    }
}
