<?php

namespace App\DataFixtures;

use App\Entity\Plat;
use App\Entity\Restorer;
use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
  private $encoder;

  public function __construct(UserPasswordEncoderInterface $encoder)
  {
    $this->encoder = $encoder;
  }
    public function load(ObjectManager $manager)
    {
    $faker = Faker\Factory::create();
    for ($i = 0; $i < 24; $i++) {
      $address = new Address();
      $address
        ->setStreet($faker->streetAddress())
        ->setZipcode($faker->postcode())
        ->setcity($faker->city());
      $manager->persist($address);
  
      $restorer = new Restorer();
      $restorer
        ->setRestaurantName($faker->state())
        ->setImage($faker->imageUrl(200,140))
        ->setEmail($faker->email())
        ->setPassword($this->encoder->encodePassword(
          $restorer,'lolilol'))
        ->setRoles(["ROLE_RESTORER"])
        ->setAddress($address);
      $manager->persist($restorer);

      for ($j = 0; $j < 4; $j++) {
        $plat = new Plat();
        $plat
          ->setRestaurant($restorer)
          ->setNom($faker->state())
          ->setDescription($faker->text(50))
          ->setImage($faker->imageUrl(200,140))
          ->setPrice($faker->randomFloat(2,$min = 2.5, $max = 20));
        $manager->persist($plat);
      }
    }
    $manager->flush();
  }


    

        
}
