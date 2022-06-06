<?php

namespace App\DataFixtures;

use App\Entity\Categories as EntityCategories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoriesFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Initialisation du bundle Faker
        $faker = Faker\Factory::create('fr_FR');

        // Ajout de plusieurs éléments en BDD
        for($i = 1; $i <= 13; $i++) {

            $categorie = new EntityCategories();
            $categorie->setNom($faker->word(3, true));

            $manager->persist($categorie);

            // Enregistrement de l'annonce en référence
            $this->addReference('category_'.$i, $categorie);

        }
        $manager->flush();
    }

}