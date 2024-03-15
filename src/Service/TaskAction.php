<?php
namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TaskAction 
{
        public function __construct(private EntityManagerInterface $em, private Security $security) {}
        public function saveNewTask(Task $task)
        {
            $user = $this->security->getUser();
            $task->setAuthor($user);
            $this->em->persist($task);
            $this->em->flush();
            return $task;
        }
}