<?php

namespace App\DataFixtures;

use App\Entity\Details;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PetitsDetailsFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Initialisation du bundle Faker
        $faker = Faker\Factory::create('fr_FR');

        // Ajout de plusieurs éléments en BDD
        for($i = 1; $i <= 135; $i++) {

            $petitsDetails = new \App\Entity\PetitesDetails();

            $petitsDetails->setNom($faker->word(3, true));

            $petitsDetails->setDetails($this->getReference('details_'.$faker->numberBetween(1, 70)));

            $manager->persist($petitsDetails);

            // Enregistrement de l'annonce en référence
            $this->addReference('petit_details__'.$i, $petitsDetails);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DetailsFixture::class,
        ];
    }


}