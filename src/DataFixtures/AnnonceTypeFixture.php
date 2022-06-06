<?php

namespace App\DataFixtures;

use App\Entity\AnnonceType as EntityAnnonceType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AnnonceTypeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Initialisation du bundle Faker
        $faker = Faker\Factory::create('fr_FR');

        // Ajout de plusieurs éléments en BDD
        for($i = 1; $i <= 2; $i++) {

            $type = new EntityAnnonceType();
            $type->setNom($faker->word(3, true));

            $manager->persist($type);

            // Enregistrement de l'annonceType en référence
            $this->addReference('type_'. $i, $type);

        }
        $manager->flush();
    }

}