<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Message;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //use faker
        $faker = Factory::create('fr_FR');
        $faker->seed(0);

        $user1 = $this->getReference('user1');
        $user2 = $this->getReference('user2');

        $message1 = new Message();
        $message1
            ->setContent($faker->paragraphs(3, true))
            ->setCreatedAt($faker->dateTimeBetween('-2 years', 'now', 'Europe/Paris'))
            ->setUser($user1);

        $message2 = new Message();
        $message2
            ->setContent($faker->paragraphs(3, true))
            ->setCreatedAt($faker->dateTimeBetween('-2 years', 'now', 'Europe/Paris'))
            ->setUser($user2);

        $manager->persist($message1);
        $manager->persist($message2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
