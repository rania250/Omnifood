<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un utilisateur admin
        $adminUser = new User();
        $adminUser->setEmail('admin@example.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setFullName('Admin User'); // Ajoutez cette ligne pour définir le nom complet
        $adminUser->setPassword($this->passwordHasher->hashPassword(
            $adminUser,
            'admin_password'
        ));

        $manager->persist($adminUser);

        // Créer un utilisateur simple
        $simpleUser = new User();
        $simpleUser->setEmail('user@example.com');
        $simpleUser->setRoles(['ROLE_USER']);
        $simpleUser->setFullName('Simple User'); // Ajoutez cette ligne pour définir le nom complet
        $simpleUser->setPassword($this->passwordHasher->hashPassword(
            $simpleUser,
            'user_password'
        ));

        $manager->persist($simpleUser);

        $manager->flush();
    }
}
