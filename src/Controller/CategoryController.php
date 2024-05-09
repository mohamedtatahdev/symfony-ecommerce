<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'category')]
    public function index($slug, CategoryRepository $categoryRepository,): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);//recupere les cat en fonction du slug

        if(!$category){
            return $this->redirectToRoute('home');//redirige la route si la cat n'existe pas
        }

        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
