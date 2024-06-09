<?php
namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\HttpFoundation\RequestStack;

class UserCrudController extends AbstractCrudController
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private TokenStorageInterface $tokenStorage;
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            // Champs pour les vues index et détail
            $fields[] = ImageField::new('photoProfil', 'Photo de profil')
                ->setBasePath('uploads')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false);
            $fields[] = TextField::new('fullName', 'Nom complet');
            $fields[] = TextField::new('email');
            $fields[] = TelephoneField::new('telephone', 'Numéro de téléphone');
            $fields[] = TextareaField::new('adresse', 'Adresse');
            $fields[] = ChoiceField::new('roles', 'Rôle')
                ->allowMultipleChoices()
                ->renderAsBadges([
                    'ROLE_ADMIN' => 'success',
                    'ROLE_USER' => 'primary',
                    'ROLE_AUTHOR' => 'warning',
                ])
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                    'Auteur' => 'ROLE_AUTHOR'
                ]);
        }

        if ($pageName === Crud::PAGE_NEW) {
            // Champs pour les formulaires de création
            $fields[] = ImageField::new('photoProfil', 'Photo de profil')
                ->setBasePath('uploads')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false);
            $fields[] = TextField::new('fullName', 'Nom complet');
            $fields[] = TextField::new('email');
            $fields[] = TextField::new('password', 'Mot de passe')
                ->setFormType(PasswordType::class);
            $fields[] = TelephoneField::new('telephone', 'Numéro de téléphone');
            $fields[] = TextareaField::new('adresse', 'Adresse');

            if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                $fields[] = ChoiceField::new('roles', 'Rôle')
                    ->setChoices([
                        'Utilisateur' => 'ROLE_USER',
                        'Administrateur' => 'ROLE_ADMIN',
                        'Auteur' => 'ROLE_AUTHOR',
                    ])
                    ->renderAsBadges([
                        'ROLE_ADMIN' => 'success',
                        'ROLE_USER' => 'primary',
                        'ROLE_AUTHOR' => 'warning',
                    ])
                    ->allowMultipleChoices()
                    ->setRequired(true);
            }
        }

        if ($pageName === Crud::PAGE_EDIT) {
            // Champs pour le formulaire d'édition
            $currentUser = $this->tokenStorage->getToken()->getUser();
            $request = $this->requestStack->getCurrentRequest();
            $userId = $request->query->get('entityId');
            $userToEdit = $this->entityManager->getRepository(User::class)->find($userId);

            if ($currentUser instanceof User && $userToEdit instanceof User) {
                $fields[] = ImageField::new('photoProfil', 'Photo de profil')
                    ->setBasePath('uploads')
                    ->setUploadDir('public/uploads')
                    ->setUploadedFileNamePattern('[randomhash].[extension]')
                    ->setRequired(false);

                if ($currentUser === $userToEdit) {
                    $fields[] = TextField::new('fullName', 'Nom complet');
                    $fields[] = TextField::new('email');
                    $fields[] = TelephoneField::new('telephone', 'Numéro de téléphone');
                    $fields[] = TextareaField::new('adresse', 'Adresse');
                } else {
                    $fields[] = TextField::new('fullName', 'Nom complet')->setFormTypeOption('disabled', true);
                    $fields[] = TextField::new('email')->setFormTypeOption('disabled', true);
                    $fields[] = TelephoneField::new('telephone', 'Numéro de téléphone')->setFormTypeOption('disabled', true);
                    $fields[] = TextareaField::new('adresse', 'Adresse')->setFormTypeOption('disabled', true);
                }

                if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                    $fields[] = ChoiceField::new('roles', 'Rôle')
                        ->setChoices([
                            'Utilisateur' => 'ROLE_USER',
                            'Administrateur' => 'ROLE_ADMIN',
                            'Auteur' => 'ROLE_AUTHOR',
                        ])
                        ->renderAsBadges([
                            'ROLE_ADMIN' => 'success',
                            'ROLE_USER' => 'primary',
                            'ROLE_AUTHOR' => 'warning',
                        ])
                        ->allowMultipleChoices()
                        ->setRequired(true);
                }
            }
        }

        return $fields;
    }

    public function onBeforeEntityUpdatedEvent($event): void
    {
        $entity = $event->getEntityInstance();
        $currentUser = $this->tokenStorage->getToken()->getUser();

        if ($entity instanceof User && $entity !== $currentUser) {
            $originalUser = $this->entityManager->getRepository(User::class)->find($entity->getId());
            $entity->setFullName($originalUser->getFullName());
            $entity->setEmail($originalUser->getEmail());
        }
    }
}
