<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ->setSearchFields(['id', 'status', 'user.email'])
            ->setDefaultSort(['created_at' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $statusChoices = [
            'En attente' => '<span class="badge badge-warning">En attente</span>',
            'En cours de préparation' => '<span class="badge badge-info">En cours de préparation</span>',
            'Prête à être livrée' => '<span class="badge badge-primary">Prête à être livrée</span>',
            'Livrée' => '<span class="badge badge-success">Livrée</span>',
            'Annulée' => '<span class="badge badge-danger">Annulée</span>',
        ];

        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('created_at', 'Date de création')->setFormat('short', 'short'),
            DateField::new('updated_at', 'Date de mise à jour')->setFormat('short', 'short'),
            ChoiceField::new('status', 'Statut')
                ->setChoices($statusChoices)
                ->renderAsNativeWidget(), // Permet de modifier le statut directement dans la liste
            TextField::new('remarque', 'Remarque')->hideOnIndex(),
            MoneyField::new('totale', 'Total')->setCurrency('EUR'),
            AssociationField::new('user', 'Utilisateur')
                ->autocomplete()
                ->formatValue(function ($value, $entity) {
                    return $entity->getUser()->getFullName();
                }),
            TextField::new('user.fullName', 'Nom complet')
                ->onlyOnIndex()
                ->setTemplatePath('admin/fields/user_fullname.html.twig'),
            ImageField::new('user.photoProfil', 'Photo de profil')
                ->setBasePath('/uploads')
                ->onlyOnIndex()
                ->setTemplatePath('admin/fields/user_profile_picture.html.twig'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setLabel('Save and return');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->setLabel('Save and continue');
            });
    }
}
