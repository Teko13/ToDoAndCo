<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['groupe1', 'group_app'];
    }
    public function __construct(private UserPasswordHasherInterface $hasher) {}
    public function load(ObjectManager $manager): void
    {
        $admin = new User;
        $admin->setEmail("admin@gmail.com")->setUsername("admin")->setPassword($this->hasher->hashPassword($admin, "admin"))->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);
        $user = new User;
        $user->setUsername("user")->setEmail("user@gmail.com")->setRoles(["ROLE_USER"])->setPassword($this->hasher->hashPassword($user, "user"));
        $manager->persist($user);
        $anonymous = new User;
        $anonymous->setUsername("anonymous")->setEmail("anonymous@gmail.com")->setRoles([])->setPassword($this->hasher->hashPassword($user, "anonymous"));
        $manager->persist($anonymous);
        for ($i = 0; $i < 3; $i++)
        {
            // Create admin task
            $adminTask = new Task;
            $adminTask->setTitle("Admin task $i")->setContent("This is admin task")->setAuthor($admin);
            $manager->persist($adminTask);
            // Create user task
            $userTask = new Task;
            $userTask->setTitle("User task $i")->setContent("This is user task")->setAuthor($user);
            $manager->persist($userTask);
            // Create anonymous user task
            $anonymousTask = new Task;
            $anonymousTask->setTitle("Anonymous task $i")->setContent("This is anonymous task")->setAuthor($anonymous);
            $manager->persist($anonymousTask);
        }


        $manager->flush();
    }
}
