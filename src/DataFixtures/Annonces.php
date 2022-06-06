<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class Annonces extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Initialisation du bundle Faker
        $faker = Faker\Factory::create('fr_FR');

        // Ajout de plusieurs éléments en BDD
        for($i = 0; $i <= 50; $i++) {

            // $photo = $faker->image('public/uploads/annonces');

            $annonce = new \App\Entity\Annonces();

            $annonce->setTitre($faker->word(3, true));
            $annonce->setLieu($faker->city);
            $annonce->setDescription($faker->realText(400));
            // $annonce->setImages($faker->numberBetween(0, 1) == 1 ? str_replace('public/uploads/annonces/', '', $photo) : null);
            // $annonce->setImages($faker->imageUrl($width = 640, $height = 480, 'cats'));
            $annonce->setImages('blog-1.jpeg');
            $annonce->setCreatedAt($faker->dateTime($max = 'now', $timezone = null));

            $annonce->setCategorie($this->getReference('category_'.$faker->numberBetween(1, 13)));
            $annonce->setAnnonceType($this->getReference('type_'.$faker->numberBetween(1, 2)));
            $annonce->setUser($this->getReference('user_'.$faker->numberBetween(1, 50)));

            $manager->persist($annonce);

            // Enregistrement de l'annonce en référence
            $this->addReference('annonce_'. $i, $annonce);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoriesFixture::class,
            AnnonceTypeFixture::class,
            UserFixture::class
        ];
    }
}