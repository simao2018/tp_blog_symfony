<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $articles = new Article();
            $articles
                ->setTitle($faker->words(8, true))
                ->setContent($faker->sentences(3, true));
            $manager->persist($articles);
        }

        $manager->flush();
    }
}
