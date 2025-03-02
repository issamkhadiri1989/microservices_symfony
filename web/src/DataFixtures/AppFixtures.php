<?php

namespace App\DataFixtures;

use App\Factory\GenreFactory;
use App\Factory\MovieFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        GenreFactory::createMany(10);
        MovieFactory::createMany(60);
        $manager->flush();
    }
}
