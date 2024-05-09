<?php

namespace App\Controller\Account;

use App\Form\ModifyPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ModifyPasswordController extends AbstractController
{
    #[Route('/compte/modifier-mot-de-passe', name: 'modify_pwd')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ModifyPasswordType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success','Votre mot de passe a été modifié avec succes' );

            $em->flush();
        }
        return $this->render('account/password/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}