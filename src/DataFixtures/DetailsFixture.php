<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class DetailsFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Initialisation du bundle Faker
        $faker = Faker\Factory::create('fr_FR');

        // Ajout de plusieurs éléments en BDD
        for($i = 1; $i <= 70; $i++) {

            $details = new \App\Entity\Details();
            $details->setNom($faker->word(3, true));

            $details->setCategoriesDetails($this->getReference('category_details_'.$faker->numberBetween(1, 35)));

            $manager->persist($details);

            // Enregistrement de l'annonce en référence
            $this->addReference('details_'.$i, $details);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategorieDetailsFixture::class,
        ];
    }


}