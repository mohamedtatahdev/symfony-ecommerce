<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
         ->setEntityLabelInSingular('Commande')
         ->setEntityLabelInPlural('Commandes')
        ;
    }
    
    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('Afficher')->linkToCrudAction('show');


        return $actions
                ->add(Crud::PAGE_INDEX, $show) 
                ->remove(Crud::PAGE_INDEX, Action::NEW)
                ->remove(Crud::PAGE_INDEX, Action::EDIT)
                ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date'),
            NumberField::new('state')->setLabel('Status'),
            AssociationField::new('user')->setLabel('Utilisateur'),
            TextField::new('carrierName')->setLabel('Transporteur'),
            NumberField::new('totalTva')->setLabel('total TVA'),
            NumberField::new('totalWt')->setLabel('total TTC')
        ];
    }

    public function show(AdminContext $context, AdminUrlGenerator $adminUrlGenerator, Request $request)
    {
        $order = $context->getEntity()->getInstance();

        // Récupérer l'URL de notre action "SHOW"
        $url = $adminUrlGenerator->setController(self::class)->setAction('show')->setEntityId($order->getId())->generateUrl();

        // Traitement des changements de statut
        if ($request->get('state')) {
            $this->changeState($order,$request->get('state'));
        }


        return $this->render('admin/order.html.twig', [
            'order' => $order,
            'current_url' => $url
        ]);
    }


    
}
