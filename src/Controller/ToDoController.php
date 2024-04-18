<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/todo')]
class ToDoController extends AbstractController
{
    #[Route('/', name: 'app_to_do')]
    public function index(Request $request): Response
    {
        $todos = array(
            "Achat" => "Acheter un clé USB",
            "Sport" => "Marcher 2 Km",
            "Etude" => "Faire révision"
        );
        $session = $request->getSession();
        if (!$session->has("todos")) {
            $session->set("todos", $todos);
        }
        return $this->render('todo/listeToDo.html.twig');
    }

    #[Route('/add/{name?samedi}/{content?sport}', name: 'todo.add')]
    public function addToDo(Request $request, $name, $content): Response
    {
        $session = $request->getSession();
        if (!$session->has("todos")) {
            $this->addFlash("info", "Liste est initialisé!");
            return $this->redirectToRoute("app_to_do");
        } else {
            $todos = $session->get("todos");
            if (isset($todos[$name])) {
                $this->addFlash("error", "La Tâche  est déja ajoutée !");
            } else {
                //$todos = $session->get("todos");
                $todos[$name] = $content;
                $session->set("todos", $todos);
                $this->addFlash("success", "Tâche est ajoutée avec succés !");
            }
        }
        return $this->render('todo/listeToDo.html.twig');
    }


    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateToDo(Request $request, $name, $content): Response
    {
        $session = $request->getSession();
        if (!$session->has("todos")) {
            $this->addFlash("info", "Liste est initialisé!");
            return $this->redirectToRoute("app_to_do");
        } else {
            $todos = $session->get("todos");
            if (isset($todos[$name])) {
                $todos[$name] = $content;
                $session->set("todos", $todos);
                $this->addFlash("success", "La Tâche  est modifiée avec succés !");
            } else {
                $session->set("todos", $todos);
                $this->addFlash("error", "Tâche est introuvable !");
            }
        }
        return $this->render('todo/listeToDo.html.twig');
    }

    #[Route('/delete/{name}', name: 'todo.update')]
    public function deleteToDo(Request $request, $name): Response
    {
        $session = $request->getSession();
        if (!$session->has("todos")) {
            $this->addFlash("info", "Liste est initialisé!");
            return $this->redirectToRoute("app_to_do");
        } else {
            $todos = $session->get("todos");
            if (isset($todos[$name])) {
                unset($todos[$name]);
                $session->set("todos", $todos);
                $this->addFlash("success", "La Tâche  est supprimée avec succés !");
            } else {
                $session->set("todos", $todos);
                $this->addFlash("error", "Tâche est introuvable !");
            }
        }
        return $this->render('todo/listeToDo.html.twig');
    }


    #[Route('/reset', name: 'todo.reset')]
    public function resetToDo(Request $request): Response
    {
        $session = $request->getSession();
        $session->remove("todos");
        $this->addFlash("info", "Liste est initialisé!");
        return $this->render('todo/listeToDo.html.twig');
    }
}
