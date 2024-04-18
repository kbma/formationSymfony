<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PersonneFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('FR-fr');
        for ($i = 1; $i <= 20; $i++) {
            $Personne = new Personne();
            $Personne->setFirstName($faker->firstName);
            $Personne->setName($faker->name);
            $Personne->setAge($faker->numberBetween(10, 90));
           /*  $Personne->setCreatedBy(null); */
            $Personne->setJob(null);
            $Personne->setImage('https://dummyimage.com/200x300&text='.$Personne->getName());
            $manager->persist($Personne);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        // TODO: Implement getGroups() method.
        return ['personne'];
    }
}
