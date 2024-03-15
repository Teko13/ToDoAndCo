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
        return ["group_test"];
    }
    public function __construct(private UserPasswordHasherInterface $hasher) {}
    public function load(ObjectManager $manager): void
    {
        $user = new User;
        $user->setUsername("coco")->setEmail("coco@gmail.com")->setPassword("coco@gmail.com")->setPassword($this->hasher->hashPassword($user, 'test'));
        $manager->persist($user);
        $task = new Task;
        $task->setTitle("title1")->setContent("content1")->setAuthor($user);
        $manager->persist($task);
        $manager->flush();
    }   
}

