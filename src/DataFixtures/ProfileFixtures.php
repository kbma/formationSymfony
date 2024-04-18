<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {  
        $faker = Factory::create('FR-fr');
        for ($i = 1; $i <= 20; $i++) {
            $profile = new Profile();
            $profile->setRs($faker->word);
            $profile->setUrl($faker->word.'.com');
            
            $manager->persist($profile);
        }
        $manager->flush();
    }
}
