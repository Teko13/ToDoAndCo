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
        // To ensure we have a unique username for each test, we generate a random ID as a suffix to the username.
        $form["user[username]"] = "username_". uniqid();
        $form["user[email]"] = "test@gmail.com";
        $form["user[password][first]"] = "password";
        $form["user[password][second]"] = "password";
        $client->submit($form);
        $this->assertResponseRedirects($redirectionUrl);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
    }
    public function testEdit(): void
    {
        $client = static::createClient();
        $userRepos = static::getContainer()->get(UserRepository::class);
        $admin = $userRepos->findOneBy(["username" => "admin"]);
        $user = $userRepos->findOneBy(["username" => "user"]);
        $router = static::getContainer()->get(RouterInterface::class);
        $userEditionRoute = $router->generate("edit_user", ["id" => $user->getId()], RouterInterface::ABSOLUTE_PATH);
        $redirectionUrl = $router->generate("users_list");
        $client->loginUser($admin);
        $crawler = $client->request("GET", $userEditionRoute);
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton("Modifier")->form();
        $form["user[email]"] = "test-edit-user-email@gmail.com";
        $form["user[password][first]"] = "password";
        $form["user[password][second]"] = "password";
        $client->submit($form);
        $this->assertResponseRedirects($redirectionUrl);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
    }
}
