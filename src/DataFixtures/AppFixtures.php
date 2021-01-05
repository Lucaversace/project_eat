<?php

namespace App\DataFixtures;

use App\Entity\Plat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    $faker = Faker\Factory::create();

    for ($i = 0; $i < 25; $i++) {
      $plat = new Plat();
      $plat
        ->setNom($faker->state())
        ->setDescription($faker->text(70))
        ->setImage($faker->imageUrl($width = 200, $height = 140))
        ->setPrice($faker->randomDigit());
      $manager->persist($plat);
    }

        $manager->flush();
    }
}
