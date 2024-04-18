<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HobbyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {  
        $faker = Factory::create('FR-fr');
        for ($i = 1; $i <= 20; $i++) {
            $hobby = new Hobby();
            $hobby->setDesignation($faker->word);
            
            $manager->persist($hobby);
        }
        $manager->flush();
    }
}
