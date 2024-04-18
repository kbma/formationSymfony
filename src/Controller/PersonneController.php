<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonnesEvent;
use App\Form\PersonneType;
use App\Service\UploaderService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\PdfService;
use function PHPSTORM_META\type;
use App\Service\Calculator;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    public function __construct(private EventDispatcherInterface $eventDispatcherInterface,
       Calculator $calculator)
    {
        $this->calculator = $calculator;
    }


    #[Route('/pdf/{id}', name: 'personne.pdf')]
    public function pdf(PdfService $PdfService, ManagerRegistry  $doctrine, Personne $personne)
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($personne);

        $imageSrc=null;
        if ($personne->getImage()) {
            $imageSrc  = $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/uploads/personnes/' . $personne->getImage());
        }

        $html =  $this->renderView('personne/pdf.html.twig', compact('imageSrc', 'personne'));

        $PdfService->showpdf($html);
    }

    private function imageToBase64($path)
    {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }




    #[Route('/add/{id<\d+>?0}', name: 'personne.add')]
    public function addPersonne(UploaderService $uploaderService, SluggerInterface $slugger, Personne $personne = null, ManagerRegistry $doctrine, Request $request): Response
    {

        $message = ' est modifiée avec succés';
        if (!$personne) {
            $personne = new Personne();
            $message = ' est ajoutée avec succés';
            
           
        }

        

        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);



       


        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $photoFile = $form->get('photo')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photoFile) {
                $directory = $this->getParameter('personnes_directory');
                $personne->setImage($uploaderService->uploadFile($photoFile, $directory));
            }
            $manager->flush();

            $addPersonneEvent =new AddPersonneEvent($personne);
        
            $this->eventDispatcherInterface->dispatch($addPersonneEvent, AddPersonneEvent::ADD_PERSONNE_EVENT);
       



            $this->addFlash('success', 'la personne ' . $personne->getFirstName() . $message);
            return $this->redirectToRoute('personne.list');
        } else {
            return $this->render('personne/add.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }


    #[Route('/update/{personne}', name: 'personne.update')]
    public function updatePersonne(ManagerRegistry $doctrine, Request $request, Personne $personne): Response
    {
        if ($personne) {
            $personne->setFirstName($request->request->get('firstName'));
            $personne->setName($request->request->get('name'));
            $personne->setAge($request->request->get('age'));
            $personne->setJob($request->request->get('job'));
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();
            $this->addFlash('success', 'La personne ' . $personne->getFirstName() . ' est modifiée avec succés');
            return $this->redirectToRoute('personne.list');
        } else {

            $this->addFlash('error', 'Cette personne est introuvable');
            return $this->redirectToRoute('personne.list');
        }
    }


    #[Route('/details/{personne}', name: 'personne.details')]
    public function personneDetails(ManagerRegistry $doctrine, Personne $personne): Response
    {
        if ($personne) {

            return $this->render('personne/details.html.twig', compact('personne'));
        } else {

            $this->addFlash('error', 'Cette personne est introuvable');
            return $this->redirectToRoute('personne.list');
        }
    }

    #[Route('/age/{min<\d+>?18}/{max<\d+>?90}', name: 'personne.age')]
    public function personneAgeInterval(ManagerRegistry $doctrine, $min, $max): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findByPersonnesByAgeInterval($min, $max);
        $this->addFlash('success', 'Les personnes dont leurs ages entre ' . $min . ' et ' . $max);
        return $this->render('personne/index.html.twig', compact('personnes'));
    }

    #[Route('/stats/{min<\d+>?18}/{max<\d+>?90}', name: 'personne.stats')]
    public function statsPersonneAgeInterval(ManagerRegistry $doctrine, $min, $max): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statsPersonnesByAgeInterval($min, $max);
        return $this->render('personne/stats.html.twig', compact('stats', 'min', 'max'));
    }


    #[Route('/modifier/{personne}', name: 'personne.modifier')]
    public function personneModifier(ManagerRegistry $doctrine, Personne $personne): Response
    {
        
        if ($personne) {
            return $this->render('personne/modifier.html.twig', compact('personne'));
        } else {

            $this->addFlash('error', 'Cette personne est introuvable');
            return $this->redirectToRoute('personne.list');
        }
    }


    #[Route('/supprimer/{personne}', name: 'personne.supprimer')]
    public function supprimerPersonne(ManagerRegistry $doctrine, Personne $personne): Response
    {
        if ($personne) {
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();
            $this->addFlash('success', 'Cette personne est supprimée avec succés');
            return $this->redirectToRoute('personne.list');
        } else {

            $this->addFlash('error', 'Cette personne est introuvable');
            return $this->redirectToRoute('personne.list');
        }
    }

    #[Route('/{page?1<d\+>}/{nbre?5<d\+>}', name: 'personne.list')]
    public function index( LoggerInterface $logger,  ManagerRegistry $doctrine, $page, $nbre): Response
    {

        //echo $this->calculator->add(10, 5);
        $logger->info("Coucou dans le fichier log");
        $nbre = intval($nbre);
        $page = intval($page);
        $repository = $doctrine->getRepository(Personne::class);
        $totalPersonnes = intval($repository->count([]));
        $totalPages = ceil($totalPersonnes / $nbre);
        $personnes = $repository->findBy([], ['id' => 'desc'], $nbre, ($page - 1) * $nbre);
        //dd('list');
        $listAllPersonnesEvent = new ListAllPersonnesEvent($totalPersonnes);
        $this->eventDispatcherInterface->dispatch($listAllPersonnesEvent, ListAllPersonnesEvent::LIST_ALL_PERSONNE_EVENT);

        return $this->render('personne/index.html.twig', compact('personnes', 'totalPersonnes', 'totalPages', 'page', 'nbre'));
    }
}
