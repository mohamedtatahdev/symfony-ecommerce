<?php

namespace App\Controller\Account;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    #[Route('/compte/adresse', name: 'address')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig');
    }

    #[Route('/compte/adresse/delete/{id}', name: 'address_delete')]
    public function delete($id, AddressRepository $addressRepository,EntityManagerInterface $em): Response
    {
        $address = $addressRepository->findOneById($id);
        $address = $addressRepository->findOneById($id);
        if(!$address OR $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('address');
        }
        $this->addFlash('success','Votre adresse est correctement supprimer' );
        $em->remove($address);
        $em->flush($address);
        return $this->redirectToRoute('address');

    }
    #[Route('/compte/adresse/ajouter/{id}', name: 'address_form', defaults: ['id' => null] )]
    public function form(Request $request, EntityManagerInterface $em, $id, AddressRepository $addressRepository, Cart $cart): Response
    {
        if($id){
            $address = $addressRepository->findOneById($id);
                if(!$address OR $address->getUser() != $this->getUser()){
                    return $this->redirectToRoute('address');
                }
        }else{
             $address = new Address;
            $address->setUser($this->getUser());
        }
       

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->addFlash('success','Votre adresse a été ajouté avec succes' );

            $em->persist($address);
            $em->flush();

            if($cart->fullQuantity() > 0){
                return $this->redirectToRoute('order');
            }
            
            return $this->redirectToRoute('address');
        }
        return $this->render('account/address/form.html.twig',[
            'form' => $form
        ]);
    }
}