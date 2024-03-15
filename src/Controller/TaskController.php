<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    public function __construct(private TaskAction $taskAction, private EntityManagerInterface $em) {}
    #[Route('/tasks', name: 'tasks_list')]
    public function tasksList(): Response
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $this->em->getRepository(Task::class)->findAll(),
        ]);
    }
    #[Route("/tasks/create", name: "create_task")]
    public function createAction(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskAction->save($task);
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            return $this->redirectToRoute('tasks_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }
    #[Route("/tasks/{id}/toggle", name: "toggle_task")]
    public function toggleTaskAction(Request $request):Response
    {
        $task = $this->em->getRepository(Task::class)->findOneBy(["id" => $request->attributes->get("id")]);
        $task->toggle(!$task->isDone());
        $this->em->persist($task);
        $this->em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('tasks_list');
    }
    #[Route("/tasks/{id}/delete", name: "delete_task")]
    public function deleteTaskAction(Request $request):Response
    {
        $task = $this->em->getRepository(Task::class)->findOneBy(["id"=>$request->attributes->get("id")]);
        $this->em->remove($task);
        $this->em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('tasks_list');
    }
    #[Route("/tasks/{id}/edit", name: "edit_task")]
    public function editAction(Request $request):Response
    {
        $task = $this->em->getRepository(Task::class)->findOneBy(["id"=>$request->attributes->get("id")]);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskAction->save($task);
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            return $this->redirectToRoute('tasks_list');
        }
        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }
}
