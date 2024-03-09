<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
        $user = $userRepo->findOneBy(["username" => "coco"]);
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
}
