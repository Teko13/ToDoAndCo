<?php
namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TaskService 
{
        public function __construct(private EntityManagerInterface $em, private Security $security) {}
        public function save(Task $task): Task
        {
            // do not assigned task's author if is already set (edit edit context)
            if(!$task->getAuthor())
            {
                $user = $this->security->getUser();
                $task->setAuthor($user);
            }
            $this->em->persist($task);
            $this->em->flush();
            return $task;
        }
        public function delete(Task $task): void
        {
            $this->em->remove($task);
            $this->em->flush();
        }
}
