<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class TaskServiceTest extends TestCase
{
    public function testSaveNewTask(): void
    {
        // Mocking methode dependences
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $emMock = $this->createMock(EntityManagerInterface::class);
        $user = new User();
        $user->setUsername("test");
        //Mocks configuration
        $securityMock->method("getUser")->willReturn($user);
        $emMock->expects($this->once())->method("persist")->with($this->isInstanceOf(Task::class));
        $emMock->expects($this->once())->method("flush");
        // Test
        $taskService = new TaskService($emMock, $securityMock);
        $result = $taskService->save(new Task);
        // Assertion
        $this->assertNotNull($result->getAuthor());
        $this->assertEquals($result->getAuthor()->getUsername(), $user->getUsername());


    }
    public function testSaveExistingTask(): void
    {
        // Mocking methode dependences
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $emMock = $this->createMock(EntityManagerInterface::class);
        $user = new User();
        $user->setUsername("current_user");
        $author = new User;
        $author->setUsername("author");
        $task = new Task;
        $task->setAuthor($author);
        //Mocks configuration
        $securityMock->method("getUser")->willReturn($user);
        $emMock->expects($this->once())->method("persist")->with($this->isInstanceOf(Task::class));
        $emMock->expects($this->once())->method("flush");
        // Test
        $taskService = new TaskService($emMock, $securityMock);
        $result = $taskService->save($task);
        // Assertion
        $this->assertEquals($result->getAuthor()->getUsername(), $author->getUsername());
    }
}
