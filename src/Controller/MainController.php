<?php

namespace App\Controller;

use App\Form\EcoleType;
use App\Entity\Ecole;
use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //The main page 
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    //************************************ Ecole request handlers //////////////////////////////////////////////////////////

    //Creation d'ecole 
    #[Route('/Ecole', name: 'Creation Ecole')]
    public function CreerEcole(Request $request): Response
    {
        $Ecole = new Ecole();
        $form = $this->createForm(EcoleType::class, $Ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dump($Ecole->getFormations());

            $this->em->persist($Ecole);
            $this->em->flush();
            $this->addFlash('message','Operation Avec Succes');
            dump($Ecole->getFormations());
            $Ecole = new Ecole();
            $form = $this->createForm(EcoleType::class, $Ecole);
        }

        return $this->render("main/ecole.html.twig", [
            'form' => $form->createView()
        ]);
    }


    //Retourner la liste des ecoles sous forme JSON 
    #[Route('/GetEcole', name: 'GetEcole')]
    public function GetEcoles(): Response
    {
        $ecoles = $this->em->getRepository(Ecole::class)->findAll();

        $data = [];

        foreach ($ecoles as $ecole) {
            $formationsData = [];
            $formations = $ecole->getFormations();
        
            foreach ($formations as $formation) {
                $formationsData[] = [
                    'name' => $formation->getNom()
                ];
            }
        
            $data[] = [
                'id' => $ecole->getId(),
                'nom' => $ecole->getNom(),
                'Adresse' => $ecole->getAdresse(),
                'formations' => $formationsData,
            ];
        }

    return new JsonResponse($data);
    }


    //La page qui consomme la liste des ecoles sous forme JSON 
    #[Route('/ListeEcoles', name: 'ListeEcoles')]
    public function listeEcoles(): Response
    {
        $ecoles = $this->em->getRepository(Ecole::class)->findAll();

        return $this->render('main/show_ecole.html.twig', [
            'ecoles' => $ecoles,
        ]);
    }


    //Supprimmer une entity ecole 
    #[Route('/delete-ecole/{id}', name: 'delete-ecole')]
    public function deletePost($id)
    {
        $ecole = $this->em->getRepository(Ecole::class)->find($id);
        
            $this->em->remove($ecole);
            $this->em->flush();
            return new JsonResponse(['success' => true]);
    }


    //Modifier une entity ecole 
    #[Route('/edit-ecole/{id}', name: 'edit-ecole')]
    public function editecole(Request $request,$id)
    {
        $ecole = $this->em->getRepository(Ecole::class)->find($id);
        $form = $this->createForm(EcoleType::class, $ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($ecole);
            $this->em->flush();
            return $this->redirectToRoute('ListeEcoles');
        }

        return $this->render("main/ecole.html.twig", [
            'form' => $form->createView()
        ]);
    }



    //************************************ FORMATIONS request handlers //////////////////////////////////////////////////////////

    //Creer une formation  
    #[Route('/Formation', name: 'Creation Formation')]
    public function CreerFormation(Request $request): Response
    {
        $Formation = new Formation();
        $form = $this->createForm(FormationType::class, $Formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($Formation);
            $this->em->flush();
            $this->addFlash('message','Operation Avec Succes');
            $Formation = new Formation();
            $form = $this->createForm(FormationType::class, $Formation);
        }

        return $this->render("main/formation.html.twig", [
            'form' => $form->createView()
        ]);
    }


    //La page qui consomme la liste des formations 
    #[Route('/ListeFormations', name: 'ListeFormations')]
    public function listeFormations(): Response
    {
        return $this->render('main/show-formation.html.twig');
    }


    
    //recupuration de la liste des formations sous forme de JSON
    #[Route('/GetData', name: 'GetData')]
    public function GetFormations(): Response
    {
        $formations = $this->em->getRepository(Formation::class)->findAll();

        $data = [];

        foreach ($formations as $formation) {
            $ecolesData = [];
            $ecoles = $formation->getEcole();
        
            foreach ($ecoles as $ecole) {
                $ecolesData[] = [
                    'name' => $ecole->getNom()
                ];
            }
        
            $data[] = [
                'id' => $formation->getId(),
                'nom' => $formation->getNom(),
                'VolumeHoraire' => $formation->getVolumeHoraire(),
                'Ecoles' => $ecolesData,
            ];
        }

    return new JsonResponse($data);
    }

    //Supprimer une entity formation 
    #[Route('/delete-formation/{id}', name: 'delete-formation')]
public function deleteFormation(Request $request, $id)
{
    $formation = $this->em->getRepository(Formation::class)->find($id);

    if (!$formation) {
        throw $this->createNotFoundException('La formation avec l\'ID ' . $id . ' n\'existe pas.');
    }

    $this->em->remove($formation);
    $this->em->flush();

    return new JsonResponse(['success' => true]);
}

    //Modifier une entity formation 
    #[Route('/edit-formation/{id}', name: 'edit-formation')]
    public function editFormation(Request $request,$id)
    {
        $formation = $this->em->getRepository(Formation::class)->find($id);
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($formation);
            $this->em->flush();
            return $this->redirectToRoute('ListeFormations');
        }

        return $this->render("main/formation.html.twig", [
            'form' => $form->createView()
        ]);
    }

    

    


    // ****** La page relation entre Entity Ecole et l'entity formation  **********////////////////////////////
    #[Route('/Relations', name: 'Relations')]
    public function Relations(): Response
    {
        return $this->render('main/Relations.html.twig');
    }

    
}
