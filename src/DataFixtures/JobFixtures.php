<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {  
        $faker = Factory::create('FR-fr');
        for ($i = 1; $i <= 20; $i++) {
            $Job = new Job();
            $Job->setDesignation($faker->word);
            
            $manager->persist($Job);
        }
        $manager->flush();
    }
}
