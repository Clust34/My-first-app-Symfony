<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        // Va permettre de hasher le mot de pass pour le login
        private UserPasswordHasherInterface $hasher
    ) {
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // user admin
        $user = new User();

        $user
            ->setEmail('nico@test.com')
            ->setFirstName('Nicolas')
            ->setLastName('Chapuis')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->hasher->hashPassword(new User, 'az')
            );

        //Ca le prepare
        $manager->persist($user);

        // On créer 10 users fictifs
        for ($i = 1; $i <= 10; $i++) {
            $user = new user();
            $user
                ->setEmail("user-$i.com")
                ->setFirstName("User $i")
                ->setLastName('Test')
                ->setPassword(
                    $this->hasher->hashPassword(new User, 'az')
                );
            $manager->persist($user);
        }
        // Ca l'envoie en base de données
        $manager->flush();
    }
}
