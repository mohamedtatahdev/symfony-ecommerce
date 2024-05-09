<?php

namespace App\Twig;

use App\Classe\Cart;
use Twig\TwigFilter;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{
    private $categoryRepository;
    private $cart;

    public function __construct(CategoryRepository $categoryRepository, Cart $cart)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cart = $cart;
    }

    public function getFilters()
    {
        return[
            new TwigFilter('price',[$this,'formatPrice'])
        ];
    }

    public function formatPrice($number)
    {
        return number_format($number, '2', ','). '€';//eviter d'utiliser le format de partout
    }

    public function getGlobals(): array
    {
        return [
            'allCategories' => $this->categoryRepository->findAll(), //eviter de recuperer les categories dans tous les controller
            'fullCartQuantity' => $this->cart->fullQuantity(), //eviter de recuperer les categories dans tous les controller
        ];
    }



}