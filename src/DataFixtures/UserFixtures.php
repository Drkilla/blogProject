<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures
    extends Fixture
{
    private  $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load( ObjectManager $manager ): void
    {
        $userCreation = new User();
        $userCreation->setEmail('admin@admin.fr');
        $userCreation->setRoles(["ROLE_ADMIN"]);
        $userCreation->setFirstname('Admin');
        $userCreation->setLastname('admin');
        $password = $this->hasher->hashPassword($userCreation,'123456');
        $userCreation->setPassword($password);
        $manager->persist($userCreation);


        $readerCreation = new User();
        $readerCreation->setEmail('reader@reader.fr');
        $readerCreation->setRoles(["ROLE_USER"]);
        $readerCreation->setFirstname('reader');
        $readerCreation->setLastname('reader');
        $password = $this->hasher->hashPassword($readerCreation,'123456');
        $readerCreation->setPassword($password);
        $manager->persist($readerCreation);
        $manager->flush();

    }
}


