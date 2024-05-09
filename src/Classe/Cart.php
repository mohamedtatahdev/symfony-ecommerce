<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{

    public function __construct(private RequestStack $requestStack)
    {
            
    }

     /**
      * fonctino qui permet d'ajouter un produit au panier
      *
      * @param [type] $product
      * @return void
      */
    public function add($product)
    {  

        $cart = $this->getCart();
        if(isset($cart[$product->getId()])){
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        } else {
              $cart[$product->getId()] = [
            'object' => $product,
            'qty' => 1
        ];
        }
      

        $this->requestStack->getSession()->set('cart', $cart);
    }

    /**
     * fonctino qui permet de soustraire un article du panier
     *
     * @param [type] $id
     * @return void
     */
    public function decrease($id)
    {  

        $cart = $this->getCart();
     if($cart[$id]['qty']>1){
        $cart[$id]['qty'] = $cart[$id]['qty']-1;
     } else {
        unset($cart[$id]);
     }
      

        $this->requestStack->getSession()->set('cart', $cart);
    }



    /**
     * affiche le nbr de quantite
     *
     * @return int 
     */
    public function fullQuantity()//affiche le nbr de quantite
    {
       $cart = $this->requestStack->getSession()->get('cart');;
        $quantity = 0;

        if(!isset($cart)){
            return $quantity;
        }
        foreach($cart as $product){
            $quantity = $quantity + $product['qty'];
        }
        return $quantity;
    }

    /**
     * affiche le prix total du panier
     *
     * @return int
     */
    public function getTotalWt() 
    {
        $cart = $this->requestStack->getSession()->get('cart');;
        $price = 0;

        if(!isset($cart)){
            return $price;
        }
        foreach($cart as $product){
            $price = $price + ($product['object']->getPriceWt() * $product['qty']);
        }
        return $price;
    }

        /**
     * fonction qui retourne le panier
     *
     * @return void
     */
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}