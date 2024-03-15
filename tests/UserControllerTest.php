<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class UserControllerTest extends WebTestCase
{
    public function testUserManagementPageDeniedAccess(): void
    // We will attempt to access the user management page with a non-admin user to verify that access is denied.
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get(UserRepository::class);
        $router = static::getContainer()->get(RouterInterface::class);
        $userManagementRoute = $router->generate("users_list",[], RouterInterface::ABSOLUTE_PATH);
        $user = $userRepo->findOneBy(["username" => "user"]);
        $client->loginUser($user);
        $client->request('GET', $userManagementRoute);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertSelectorTextContains("h1", "403");
        $this->assertSelectorTextContains("p", "Action non autorisé.");
        
    }
    public function testUserManagementPageAccessibility(): void
    // We ensure that the management pages are accessible to users with ROLE_ADMIN.
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get(UserRepository::class);
        $router = static::getContainer()->get(RouterInterface::class);
        $userManagementRoute = $router->generate("users_list",[], RouterInterface::ABSOLUTE_PATH);
        $user = $userRepo->findOneBy(["username" => "admin"]);
        $client->loginUser($user);
        $client->request('GET', $userManagementRoute);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
    }
    public function testCreate(): void
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get(UserRepository::class);
        $router = static::getContainer()->get(RouterInterface::class);
        $redirectionUrl = $router->generate("users_list");
        $userCreationRoute = $router->generate("create_user",[], RouterInterface::ABSOLUTE_PATH);
        $user = $userRepo->findOneBy(["username" => "admin"]);
        $client->loginUser($user);
        $crawler = $client->request('GET', $userCreationRoute);
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton("Ajouter")->form();
        $form["user[username]"] = "test";
        $form["user[email]"] = "test@gmail.com";
        $form["user[password][first]"] = "password";
        $form["user[password][second]"] = "password";
        $client->submit($form);
        $this->assertResponseRedirects($redirectionUrl);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
    }
}
