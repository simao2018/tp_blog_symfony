<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public $encoder;
    public const USER_REFERENCE_1 = 'user1';
    public const USER_REFERENCE_2 = 'user2';
    public const ADMIN_REFERENCE = 'userAdmin';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(0);

        $userRole = new Role();
        $userRole->setName("ROLE_USER");
        $manager->persist($userRole);

        $manager->flush();

        $adminRole = new Role();
        $adminRole->setName("ROLE_ADMIN");

        $manager->persist($adminRole);
        $manager->flush();

        $user1 = new User();
        $user1->setEmail($faker->safeEmail)
            ->setPassword($this->encoder->encodePassword($user1, '1234'))
            ->addRole($userRole)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setPhoneNumber($faker->phoneNumber)
            ->setCreatedAt($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'));

        $manager->persist($user1);
        $manager->flush();

        $this->addReference(self::USER_REFERENCE_1, $user1);

        $user2 = new User();
        $user2->setEmail('user@ex.com')
            ->setPassword($this->encoder->encodePassword($user2, '1234'))
            ->addRole($userRole)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setPhoneNumber($faker->phoneNumber)
            ->setCreatedAt($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'));


        $manager->persist($user2);
        $manager->flush();

        $this->addReference(self::USER_REFERENCE_2, $user2);

        $userAdmin = new User();
        $userAdmin->setEmail('admin@ex.com')
            ->setPassword($this->encoder->encodePassword($userAdmin, 'admin'))
            ->addRole($userRole)
            ->addRole($adminRole)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setPhoneNumber($faker->phoneNumber)
            ->setCreatedAt($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'));


        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference(self::ADMIN_REFERENCE, $userAdmin);
    }
}
