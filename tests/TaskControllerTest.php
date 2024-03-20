<?php

namespace App\Tests;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TaskControllerTest extends WebTestCase
{
    public function testCreate(): void
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get(UserRepository::class);
        $router = static::getContainer()->get(RouterInterface::class);
        $redirectionUrl = $router->generate("tasks_list");
        $taskCreationRoute = $router->generate("create_task",[], RouterInterface::ABSOLUTE_PATH);
        $user = $userRepo->findOneBy(["username" => "admin"]);
        $client->loginUser($user);
        $crawler = $client->request('GET', $taskCreationRoute);
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton("Ajouter")->form();
        $form["task[title]"] = "Simulated title";
        $form["task[content]"] = "simulated content";
        $client->submit($form);
        $this->assertResponseRedirects($redirectionUrl);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
    }
    public function testToggleTask(): void
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get(UserRepository::class);
        $taskRepo = static::getContainer()->get(TaskRepository::class);
        $router = static::getContainer()->get(RouterInterface::class);
        $redirectionUrl = $router->generate("tasks_list");
        $task = $taskRepo->findOneBy([]);
        $toggleTaskRoute = $router->generate("toggle_task",["id" => $task->getId()], RouterInterface::ABSOLUTE_PATH);
        $user = $userRepo->findOneBy(["username" => "admin"]);
        $client->loginUser($user);
        $client->request('GET', $toggleTaskRoute);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
    }
    public function testEdit():void
    {
        $client = static::createClient();
        $taskRepos = static::getContainer()->get(TaskRepository::class);
        $userRepos = static::getContainer()->get(UserRepository::class);
        $user = $userRepos->findOneBy(["username" => "admin"]);
        $router = static::getContainer()->get(RouterInterface::class);
        $task = $taskRepos->findOneBy([]);
        $taskEditionRoute = $router->generate("edit_task", ["id" => $task->getId()], RouterInterface::ABSOLUTE_PATH);
        $redirectionUrl = $router->generate("tasks_list");
        $client->loginUser($user);
        $crawler = $client->request("GET", $taskEditionRoute);
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton("Modifier")->form();
        $form["task[title]"] = "title1";
        $client->submit($form);
        $this->assertResponseRedirects($redirectionUrl);
        $client->followRedirect();
        $this->assertSelectorTextContains("div.alert.alert-success", "Superbe ! La tâche a bien été modifiée.");
        
    }
    public function testUnautorizedTaskDeletion(): void
    {
        //We will attempt to modify the task of another user by logging in with a user that has the ROLE_USER
        $client = static::createClient();
        $taskRepos = static::getContainer()->get(TaskRepository::class);
        $userRepos = static::getContainer()->get(UserRepository::class);
        $admin = $userRepos->findOneBy(["username" => "admin"]);
        $user = $userRepos->findOneBy(["username" => "user"]);
        $router = static::getContainer()->get(RouterInterface::class);
        $task = $taskRepos->findOneBy(["author" => $admin->getId()]);
        $taskDeletionRoute = $router->generate("delete_task", ["id" => $task->getId()], RouterInterface::ABSOLUTE_PATH);
        $client->loginUser($user);
        $client->request("GET", $taskDeletionRoute);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertSelectorTextContains("h1", "403");
        $this->assertSelectorTextContains("p", "Action non autorisé.");
    }
    public function testTaskDeletion(): void
    {
        $client = static::createClient();
        $taskRepos = static::getContainer()->get(TaskRepository::class);
        $userRepos = static::getContainer()->get(UserRepository::class);
        $user = $userRepos->findOneBy(["username" => "admin"]);
        $router = static::getContainer()->get(RouterInterface::class);
        $task = $taskRepos->findOneBy([]);
        $redirectionUrl = $router->generate("tasks_list");
        $taskDeletionRoute = $router->generate("delete_task", ["id" => $task->getId()], RouterInterface::ABSOLUTE_PATH);
        $client->loginUser($user);
        $client->request("GET", $taskDeletionRoute);
        $this->assertResponseRedirects($redirectionUrl);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

