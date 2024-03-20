<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $router = static::getContainer()->get(RouterInterface::class);
        $loginRoute = $router->generate("app_login", [], RouterInterface::ABSOLUTE_PATH);
        $redirectionUrl = $router->generate("home_page");
        $crawler = $client->request("GET", $loginRoute);
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton("Connexion")->form();
        $form["username"] = "admin";
        $form["password"] = "test";
        $client->submit($form);
        $this->assertResponseRedirects($redirectionUrl);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
    }
}
