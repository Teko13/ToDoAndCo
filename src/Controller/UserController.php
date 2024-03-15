<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService, private EntityManagerInterface $em) {}
    #[Route('/users', name: 'users_list')]
    public function usersList(): Response
    {
        return $this->render('user/list.html.twig', [
            'users' => $this->em->getRepository(User::class)->findAll(),
        ]);
    }
    #[Route("/users/create", name: "create_user")]
    public function createUser(Request $request):Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('users_list');
        }
        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route("/users/{id}/edit", name:"edit_user")]
    public function editUser(Request $request): Response
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['id' => $request->attributes->get("id")]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', "L'utilisateur a bien été modifié");
            return $this->redirectToRoute('users_list');
        }
        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
