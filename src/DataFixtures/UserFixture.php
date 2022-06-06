<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
                // Initialisation du bundle Faker
                $faker = Faker\Factory::create('fr_FR');

                // Ajout de plusieurs éléments en BDD
                for($i = 0; $i <= 50; $i++) {
                    $user = new \App\Entity\User();
                    $user->setEmail($faker->email);
                    $user->setPassword($this->encoder->encodePassword($user, 'secret'));
                    $user->setIsVerified(false);
                    $user->setUsername($faker->userName);
                    $user->setTélephone($faker->phoneNumber);
                    $user->setDateInscription($faker->dateTime($max = 'now', $timezone = null));
                    $user->setTempsReponse('10 minutes');
                    $user->setAdresse($faker->address);
                    $manager->persist($user);

                    // Enregistrement de l'annonce en référence
                    $this->addReference('user_'. $i, $user);

                }
                $manager->flush();
    }
}