<?php

namespace App\Command;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'assign-anon-user',
    description: 'This command assigns an anonymous user to tasks that currently have no author.',
)]
class AppAnonUserAssignCommand extends Command
{
    protected function configure(): void
    {
        //
    }
    public function __construct(private EntityManagerInterface $em, private UserPasswordHasherInterface $hasher) {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Create anonymous user if not exist
        $userRepos = $this->em->getRepository(User::class);
        $anonymouseUser = $userRepos->findOneBy(["username" => "anonymous"]);
        if(!$anonymouseUser)
        {
            $output->writeln("Anonymous user not found");
            $output->writeln("Creating of anonymous user");
            $anonymouseUser = new User();
            $anonymouseUser->setUsername("anonymous")
            ->setPassword($this->hasher->hashPassword($anonymouseUser, "anonymous"))
            ->setEmail("anonymous@anonymous.com");
            $this->em->persist($anonymouseUser);
        }
        $taskRepos = $this->em->getRepository(Task::class);
        $tasks = $taskRepos->findBy(["author" => null]);
        foreach($tasks as $task)
        {
            $task->setAuthor($anonymouseUser);
            $this->em->persist($task);
        }
        $this->em->flush();
        $output->writeln(count($tasks). " tasks is assigned to anonymous user");
        return Command::SUCCESS;
    }
}
