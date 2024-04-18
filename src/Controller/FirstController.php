<?php

namespace App\Controller;

use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FirstController extends AbstractController
{

    #[Route("/deviner", name: "deviner")]
    public function deviner(): Response
    {
        $aleatoire = rand(0, 10);
        if ($aleatoire % 2 == 0) {
            return $this->forward("App\Controller\FirstController::index", [
                "message" => "la méthode forward : transfert"
            ]);
        } else {
            return new Response("Desolé, vous etes qui? , le numero aléatoire est :" . $aleatoire);
        }
    }



    #[Route('/first', name: 'app_first')]
    public function index($message): Response
    {
        return $this->render("first/index.html.twig", [
            "name" => "Kamel ABBASSI",
            "age" => 45,
            "auth" => true,
            "message" => $message,
            "skils" => [
                "Symfony",
                "PHP",
                "Laravel",
                "MERN"
            ]
        ]);
    }

    #[Route("message/{name}/{lastname}", name: "message")]
    public function message(Request $request, $name, $lastname): Response
    {
        //dd($request->request);
        return $this->render("first/message.html.twig", compact("name","lastname"));
    }

    #[Route("multi/{x<\d+>}/{y<\d+>}", name: "multi")]
    public function multiplication($x, $y): Response
    {
          return new Response("le résultat est ".$x*$y);
    }


    #[Route("template", name: "template")]
    public function template(): Response
    {
          return $this->render('template.html.twig');;
    }

}
