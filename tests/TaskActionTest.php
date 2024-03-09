<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use App\Service\TaskAction;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class TaskActionTest extends TestCase
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
        $taskAction = new TaskAction($emMock, $securityMock);
        $result = $taskAction->saveNewTask(new Task);
        // Assertion
        $this->assertNotNull($result->getAuthor());
        $this->assertEquals($result->getAuthor()->getUsername(), $user->getUsername());


    }
}
