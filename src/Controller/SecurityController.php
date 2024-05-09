<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
  #[Route('/inscription', name: 'signup')]
  public function signup(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response

  {
    $user = new User();
    $form = $this->createForm(SignupType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $plainPassword = $form->get('plainPassword')->getData(); // recupere les donnée du plainPassword dans le formulaire
      $hash = $passwordHasher->hashPassword($user, $plainPassword); // hash le plainpassword
      $user->setPassword($hash); // persiste le hash dans la table user
      $em->persist($user);
      $em->flush();
      $this->addFlash('success', 'Votre compte est correctement crée, veuillez vous connecter.');
      return $this->redirectToRoute('login');
    }

    return $this->render('security/signup.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route("/connexion", name: "login")]
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    if ($this->getUser()) {
      return $this->redirectToRoute('home');
    }
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/login.html.twig', [
      'last_username' => $lastUsername,
      'error' => $error
    ]);
  }

  #[Route("/deconnexion", name: "logout")]
  public function logout()
  {
  }
}
