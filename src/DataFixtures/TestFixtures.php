<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['groupe1', 'group_test'];
    }
    public function __construct(private UserPasswordHasherInterface $hasher) {}
    public function load(ObjectManager $manager): void
    {
        $admin = new User;
        $admin->setUsername("admin")->setRoles(["ROLE_ADMIN"])->setEmail("admin@gmail.com")->setPassword($this->hasher->hashPassword($admin, "test"));
        $manager->persist($admin); 
        $user = new User;
        $user->setEmail("user@gmail.com")->setUsername("user")->setPassword($this->hasher->hashPassword($user, "test"))->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $adminTask = new Task;
        $adminTask->setTitle("admin task")->setContent("adim task content")->setAuthor($admin);
        $manager->persist($adminTask);
        $userTask = new Task;
        $userTask->setTitle("user task")->setContent("user tsk content")->setAuthor($user);
        $manager->persist($userTask);

        $manager->flush();
    }
}
