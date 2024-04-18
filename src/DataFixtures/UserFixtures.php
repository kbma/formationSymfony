<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasherInterface)
    {
    }

    public static function getGroups(): array
    {
        return ['user'];
    }
    
    public function load(ObjectManager $manager): void
    {

        $admin1 = new User();
        $admin1->setUsername('kamel');
        $admin1->setEmail('abbassi.kamel@gmail.com');
        $admin1->setPassword($this->userPasswordHasherInterface->hashPassword($admin1, 'admin1'));
        $admin1->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin1);

        $admin2 = new User();
        $admin2->setUsername('abbassi');
        $admin2->setEmail('kamel.abbassi@gmail.com');
        $admin2->setPassword($this->userPasswordHasherInterface->hashPassword($admin2, 'admin2'));
        $admin2->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin2);

        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setUsername('kamel' . $i);
            $user->setEmail('user' . $i . '@gmail.com');
            $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, 'user' . $i));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
