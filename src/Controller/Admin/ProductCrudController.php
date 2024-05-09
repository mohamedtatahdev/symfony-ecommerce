<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
         ->setEntityLabelInSingular('Produit')
         ->setEntityLabelInPlural('Produits')
        ;
    }
    
    
    public function configureFields(string $pageName): iterable
    {
        $required = true;

        if ($pageName == 'edit'){
            $required = false;
        }
        return [
            TextField::new('name')
            ->setLabel('Nom')
            ->setHelp('Nom du produit'),
            SlugField::new('slug')
            ->setLabel('Url')
            ->setTargetFieldName('name')
            ->setHelp('Url du produit générer automatiquement'),
            TextEditorField::new('description')
            ->setLabel('Description')
            ->setHelp('Description de votre produit'),
            ImageField::new('picture')
            ->setLabel('Image')
            ->setHelp('L\'image de votre produit en 600x600 px')
            ->setUploadDir('/public/uploads')
            ->setBasePath('/uploads')
            ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]'),
            NumberField::new('price')
            ->setLabel('Prix H.T')
            ->setHelp('Prix du produit H.t SANS LE SIGLE €'),
            ChoiceField::new('tva')
            ->setLabel('TVA')
            ->setHelp('Taux de TVA')->setChoices([
                '5,5%' => '5.5',
                '10%' => '10',
                '20%' => '20'
            ])
            ->setRequired($required),
            AssociationField::new('category')
            ->setLabel('Catégorie')
            ->setHelp('Catégorie associée'),
        ];
    }
    
}
