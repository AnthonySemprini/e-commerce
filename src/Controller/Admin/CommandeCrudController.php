<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\CommandeProduit;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use App\Controller\Admin\CommandeProduitCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
      return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('adresse'),
            TextField::new('CP'),
            TextField::new('ville'),
            NumberField::new('numTel'),
            DateTimeField::new('dateCommande'),
            AssociationField::new('CommandeProduits')
            ->setCrudController(CommandeProduitCrudController::class)
            ->formatValue(function ($value, $entity) {
                $produits = $entity->getCommandeProduits();
                if (!$produits) {
                    return 'N/A';
                }

                return implode(', ', array_map(function ($CommandeProduit) {
                    $produit = $CommandeProduit->getProduit();
                    $nomProduit = $produit->getName(); 
                    $prix = $produit->getPrix(); 
                    $quantite = $CommandeProduit->getQtt(); 
                    $total = $prix * $quantite;
                    return "$nomProduit (Quantité: $quantite, Prix: $prix €)";
                }, $produits->toArray()));
            }),
        ];


    }
    
}
