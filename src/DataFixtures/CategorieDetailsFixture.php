<?php

namespace App\DataFixtures;

use App\Entity\CategoriesDetails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategorieDetailsFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Initialisation du bundle Faker
        $faker = Faker\Factory::create('fr_FR');

        // Ajout de plusieurs éléments en BDD
        for($i = 1; $i <= 35; $i++) {

            $categorieDetail = new \App\Entity\CategoriesDetails();
            $categorieDetail->setNom($faker->word(3, true));

            $categorieDetail->setCategories($this->getReference('category_'.$faker->numberBetween(1, 13)));

            $manager->persist($categorieDetail);

            // Enregistrement de l'annonce en référence
            $this->addReference('category_details_'.$i, $categorieDetail);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoriesFixture::class,
        ];
    }


}