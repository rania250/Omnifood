<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserCrudController extends AbstractCrudController
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            // Pour les administrateurs, affichez le champ de rôle dans le formulaire d'édition
            $fields[] = ChoiceField::new('roles', 'Rôle')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->renderAsBadges([
                    'ROLE_ADMIN' => 'success',
                    'ROLE_USER' => 'primary',
                ])
                ->allowMultipleChoices() // Permettre à l'administrateur de définir plusieurs rôles
                ->setRequired(true)
                ->onlyOnForms(); // Ne montrer ce champ que dans le formulaire d'édition
        } else {
            // Pour les utilisateurs non administrateurs, affichez uniquement les champs fullName et email
            yield TextField::new('fullName', 'Nom complet')->setFormTypeOption('disabled', true);
            yield TextField::new('email')->setFormTypeOption('disabled', true);
            yield ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->renderAsBadges([
                    'ROLE_ADMIN' => 'success',
                    'ROLE_AUTHOR' => 'warning'
                ])
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Auteur' => 'ROLE_AUTHOR'
                ]);

        }

        return $fields;
    }
}
