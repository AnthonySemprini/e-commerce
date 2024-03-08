<?php
namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            AssociationField::new('categorie') 
            ->setCrudController(CategorieCrudController::class),
            TextEditorField::new('description'),
            NumberField::new('prix'),
            AssociationField::new('images') // Assurez-vous que 'images' est le nom de la propriété dans l'entité Produit qui est liée à l'entité Image
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                // ->allowAdd()
                // ->allowDelete(),
        ];
    }
}
