<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //use faker
        $faker = Factory::create('fr_FR');
        $faker->seed(0);

        //$this->getReference('admin-role')

        $user1 = $this->getReference('user1');
        $user2 = $this->getReference('user2');

        $category1 = new Category();
        $category1->setName($faker->word());

        $category2 = new Category();
        $category2->setName($faker->word());

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->flush();

        $comments = [];
        $articles = [];
        for ($j = 0; $j < 10; $j++) {
            $article = new Article();
            $article->setTitle($faker->sentence)
                ->setContent($faker->paragraphs(4, true))
                ->setImage("https://picsum.photos/200/300")
                ->setCreatedAt($faker->dateTimeBetween('-2 years', '-2 months', 'Europe/Paris'))
                ->setUpdatedAt($faker->dateTimeBetween('-2 months', 'now', 'Europe/Paris'))
                ->setAuthor($user1)
                ->setCategory($category1)
                ->addUsersLike($user2)
                ->addUsersShare($user2);

            //$user1->addArticlesWritten($article);
            $user2->addLiked($article);
            $user2->addShared($article);

            $comment = new Comment();
            $comment->setContent($faker->sentence)
                ->setUser($user2)
                ->setArticle($article)
                ->setCreatedAt($faker->dateTimeBetween('-2 years', '-2 months', 'Europe/Paris'));


            $manager->persist($article);
            $articles[] = $article;

            $manager->persist($user2);

            $manager->persist($comment);
            $comments[] = $comment;
        }

        for ($j = 0; $j < 10; $j++) {
            $article = new Article();
            $article
                ->setTitle($faker->sentence)
                ->setContent($faker->paragraphs(4, true))
                ->setImage("https://picsum.photos/200/300")
                ->setCreatedAt($faker->dateTimeBetween('-2 years', 'now', 'Europe/Paris'))
                ->setAuthor($user2)
                ->setCategory($category2)
                ->addUsersLike($user1)
                ->addUsersShare($user1);

            //$user2->addArticlesWritten($article);
            $user1->addLiked($article);
            $user1->addShared($article);

            $comment = new Comment();
            $comment
                ->setContent($faker->sentence)
                ->setUser($user1)
                ->setArticle($article)
                ->setCreatedAt($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'));

            $manager->persist($article);
            $articles[] = $article;

            $manager->persist($user1);

            $manager->persist($comment);
            $comments[] = $comment;
        }
        $manager->flush();

        $manager->remove($user1);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
