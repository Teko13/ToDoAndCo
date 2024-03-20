<?php

namespace App\Tests;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixturesTest extends TestCase
{
    public function testLoad(): void
    {
        // Mocking methode dependences
        $managerMock = $this->createMock(ObjectManager::class);
        $hasherMock = $this->getMockBuilder(UserPasswordHasherInterface::class)->disableOriginalConstructor()->getMock();
        //Mocks configuration
        $managerMock->expects($this->exactly(12))->method("persist");
        $hasherMock->expects($this->exactly(3))->method("hashPassword");
        $managerMock->expects($this->once())->method("flush");
        // Test
        $appFixtures = new AppFixtures($hasherMock);
        $appFixtures->load($managerMock);
    }
}
